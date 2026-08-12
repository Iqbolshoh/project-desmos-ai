<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class GoogleAuthController extends Controller
{
    private const AUTH_ENDPOINT = 'https://accounts.google.com/o/oauth2/v2/auth';

    private const TOKEN_ENDPOINT = 'https://oauth2.googleapis.com/token';

    private const USERINFO_ENDPOINT = 'https://openidconnect.googleapis.com/v1/userinfo';

    /** Session key holding the anti-CSRF state we hand to Google. */
    private const STATE_KEY = 'google_oauth_state';

    /**
     * Send the user to Google's consent screen.
     */
    public function redirect(Request $request): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()->route('login')
                ->with('error', 'Google sign-in is not configured yet. Please use your email and password.');
        }

        // Google echoes this back on the callback; comparing it there is what
        // stops an attacker from feeding us a code obtained in their own flow.
        $state = Str::random(40);
        $request->session()->put(self::STATE_KEY, $state);

        $query = http_build_query([
            'client_id' => config('services.google.client_id'),
            'redirect_uri' => $this->redirectUri(),
            'response_type' => 'code',
            'scope' => 'openid profile email',
            'state' => $state,
            'access_type' => 'online',
            'prompt' => 'select_account',
        ]);

        return redirect()->away(self::AUTH_ENDPOINT.'?'.$query);
    }

    /**
     * Handle Google's redirect back: sign the user in, registering them first
     * if this is their first visit.
     */
    public function callback(Request $request): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()->route('login')
                ->with('error', 'Google sign-in is not configured yet. Please use your email and password.');
        }

        $expectedState = $request->session()->pull(self::STATE_KEY);

        if (! $expectedState || ! hash_equals($expectedState, (string) $request->query('state'))) {
            return redirect()->route('login')
                ->with('error', 'The Google sign-in session expired. Please try again.');
        }

        // The user dismissed the consent screen, or Google refused the request.
        if ($request->filled('error') || ! $request->filled('code')) {
            return redirect()->route('login')
                ->with('error', 'Google sign-in was cancelled.');
        }

        try {
            $profile = $this->fetchGoogleProfile((string) $request->query('code'));
        } catch (Throwable $e) {
            Log::error('Google OAuth failed', ['message' => $e->getMessage()]);

            return redirect()->route('login')
                ->with('error', 'Could not sign you in with Google. Please try again.');
        }

        if ($profile === null) {
            return redirect()->route('login')
                ->with('error', 'Google did not return a verified email address.');
        }

        [$user, $wasCreated] = $this->findOrCreateUser($profile);

        Auth::login($user, remember: true);
        $request->session()->regenerate();

        return redirect()->route('dashboard.index')->with(
            'success',
            $wasCreated
                ? 'Welcome! Your account has been created with Google.'
                : 'Signed in with Google.'
        );
    }

    /**
     * Exchange the authorization code for the user's Google profile.
     *
     * @return array{google_id: string, email: string, name: string, avatar: ?string, verified: bool}|null
     */
    private function fetchGoogleProfile(string $code): ?array
    {
        $token = Http::asForm()
            ->timeout(15)
            ->post(self::TOKEN_ENDPOINT, [
                'code' => $code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => $this->redirectUri(),
                'grant_type' => 'authorization_code',
            ])
            ->throw()
            ->json();

        $accessToken = $token['access_token'] ?? null;

        if (! $accessToken) {
            return null;
        }

        $profile = Http::withToken($accessToken)
            ->timeout(15)
            ->get(self::USERINFO_ENDPOINT)
            ->throw()
            ->json();

        $email = $profile['email'] ?? null;
        $googleId = $profile['sub'] ?? null;

        // An unverified Google address would let anyone claim an existing
        // account simply by naming its email, so refuse those outright.
        if (! $email || ! $googleId || ($profile['email_verified'] ?? false) !== true) {
            return null;
        }

        return [
            'google_id' => (string) $googleId,
            'email' => (string) $email,
            'name' => (string) ($profile['name'] ?? Str::before($email, '@')),
            'avatar' => $profile['picture'] ?? null,
            'verified' => true,
        ];
    }

    /**
     * Match the Google account to an existing user, or register a new student.
     *
     * @param  array{google_id: string, email: string, name: string, avatar: ?string, verified: bool}  $profile
     * @return array{0: User, 1: bool}
     */
    private function findOrCreateUser(array $profile): array
    {
        return DB::transaction(function () use ($profile) {
            $user = User::where('google_id', $profile['google_id'])->first()
                ?? User::where('email', $profile['email'])->first();

            if ($user) {
                // Link the Google account on first use, and keep the picture
                // fresh — but never overwrite the name the user chose here.
                $user->forceFill([
                    'google_id' => $profile['google_id'],
                    'avatar_url' => $profile['avatar'],
                    'email_verified_at' => $user->email_verified_at ?? now(),
                ])->save();

                $this->ensureStudentSetup($user);

                return [$user, false];
            }

            $user = User::create([
                'name' => $profile['name'],
                'email' => $profile['email'],
                'password' => null,
                'google_id' => $profile['google_id'],
                'avatar_url' => $profile['avatar'],
                'plan_id' => Plan::default()?->id,
            ]);

            $user->forceFill(['email_verified_at' => now()])->save();

            $this->ensureStudentSetup($user);

            return [$user, true];
        });
    }

    /**
     * Everyone who signs up is a student: give them the role, a profile row
     * and the default plan if any of those are missing.
     */
    private function ensureStudentSetup(User $user): void
    {
        if ($user->roles()->count() === 0) {
            $user->assignRole('student');
        }

        $user->studentProfile()->firstOrCreate([]);

        if ($user->plan_id === null) {
            $user->forceFill(['plan_id' => Plan::default()?->id])->save();
        }
    }

    private function isConfigured(): bool
    {
        return filled(config('services.google.client_id'))
            && filled(config('services.google.client_secret'));
    }

    private function redirectUri(): string
    {
        return config('services.google.redirect') ?: route('auth.google.callback');
    }
}
