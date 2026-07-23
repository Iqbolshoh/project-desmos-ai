<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RolePermissionSeeder extends Seeder
{
    // ── Platform (Super Admin only) ───────────────────────────────────────────
    private const PLATFORM_PERMISSIONS = [
        'dashboard'  => ['view'],
        'roles'         => ['view', 'create', 'edit', 'delete', 'assign'],
        'users'         => ['view', 'create', 'edit', 'delete'],
        'questions'     => ['view', 'create', 'edit', 'delete'],
        'reports'       => ['view'],
        'analytics'     => ['view'],
    ];

    // ── Manager: full access except delete ────────────────────────────────────
    private const MANAGER_PERMISSIONS = [
        'dashboard'  => ['view'],
    ];

    public function run(): void
    {
        app()['cache']->forget(config('permission.cache.key'));

        // ── 1. Platform permissions → SuperAdmin ──────────────────────────────
        $platformNames = $this->createPermissions(self::PLATFORM_PERMISSIONS);

        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions($platformNames);

        // ── 3. Manager ────────────────────────────────────────────────────────
        $managerNames = $this->createPermissions(self::MANAGER_PERMISSIONS);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions($managerNames);

        // ── 4. Student (default sign-up role, no admin permissions) ────────────
        Role::firstOrCreate(['name' => 'student', 'guard_name' => 'web']);

        // ── 7. Default SuperAdmin user ────────────────────────────────────────
        $adminUser = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'admin@vexa.uz'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('B7654321'),
            ]
        );
        $adminUser->syncRoles(['superadmin']);

        // ── 8. Demo student user ────────────────────────────────────────────────
        $studentUser = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'student@vexa.uz'],
            [
                'name'     => 'Demo Student',
                'password' => bcrypt('B7654321'),
            ]
        );
        $studentUser->syncRoles(['student']);
        $studentUser->studentProfile()->firstOrCreate([], [
            'sat_goal_score'    => 800,
            'sat_current_score' => 420,
        ]);

        $this->command?->info('✓ Permissions and roles seeded.');
        $this->command?->info('  superadmin  → ' . count($platformNames) . ' permissions');
        $this->command?->info('  manager     → ' . count($managerNames) . ' permissions');
        $this->command?->info('  Login: admin@vexa.uz / B7654321');
        $this->command?->info('  Student login: student@vexa.uz / B7654321');
    }

    private function createPermissions(array $config): array
    {
        $names = [];
        foreach ($config as $resource => $actions) {
            foreach ($actions as $action) {
                $perm    = Permission::firstOrCreate(['name' => "{$resource}.{$action}", 'guard_name' => 'web']);
                $names[] = $perm->name;
            }
        }
        return $names;
    }
}
