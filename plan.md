# Desmos AI — Production Upgrade (v2.1) Master Plan

> This master plan outlines the complete production upgrade of Desmos AI to version 2.1 following PSR-12, Laravel Best Practices, full English language standardization, database query optimizations, pagination, and strict security rate limiting.

---

## 🎯 Production Upgrade Checklist (v2.1)

### 1. English Language Standardization
- [x] Translate all PHP docblocks, inline comments, variable names, and exception messages to English.
- [x] Translate all user-facing texts, placeholders, alerts, and badges across Blade templates to English.
- [x] Translate all seeded database records (topics, questions, achievements, hints) to English.

### 2. Version Bump to v2.1
- [x] Update `composer.json` (`"version": "2.1.0"`).
- [x] Update `package.json` (`"version": "2.1.0"`).
- [x] Update brand names, footers, headers, and hero titles across all Blade layouts to `Desmos AI v2.1`.

### 3. Migrations & Seeders Optimization
- [x] Consolidate and verify all 16 `create_*` migrations in `database/migrations/`.
- [x] Add explicit database indexes on foreign keys and search columns (`user_id`, `topic_id`, `created_at`, `status`, `difficulty`, `is_diagnostic`).
- [x] Synchronize all seeders in `database/seeders/` to match the exact migration schemas.

### 4. PSR-12 & Clean Code Refactoring
- [x] Remove all inline Fully Qualified Class Names (`\App\Models\User`, `\Carbon\Carbon`, `\Str::slug`, `\Illuminate\Support\Js::from()`) across the entire codebase.
- [x] Import all classes using `use` statements at the top of every PHP file.
- [x] Format code cleanly according to PSR-12 standards.

### 5. Security & Rate Limiting (Throttling)
- [x] Enforce `throttle` middleware on every route in `routes/web.php` (`throttle:60,1` for general auth routes, `throttle:15,1` for AI endpoints, `throttle:5,1` for auth forms).

### 6. Performance & N+1 Query Optimization + Pagination
- [x] Implement `.paginate(10)` / `.paginate(15)` on all listing controllers (History, Practice, Diagnostic results, Leaderboard, Admin management).
- [x] Implement eager loading (`with([...])`) on all queries to eliminate N+1 database calls.

---

## ✅ v2.1 Completed — 2026-08-12

All six sections verified and shipped. Follow-up work delivered on top of v2.1:

- **Google Sign-In** (`GoogleAuthController`, no external package): state-checked OAuth flow, verified-email requirement, account linking by `google_id`/email, auto-registration as student on the free plan. Buttons live on the login and register pages. Requires `GOOGLE_CLIENT_ID` / `GOOGLE_CLIENT_SECRET` in `.env` (empty until credentials are issued — the button then falls back to a friendly error).
- **Subscription plans & billing**: `plans` table + `PlanSeeder` (Free: 3 AI requests/day, Premium $19/mo: unlimited), `/billing` page with live usage, admin CRUD under `/admin/plans` guarded by `plans.*` permissions, plan assignment with expiry date on the admin user form.
- **AI quota enforcement**: `ai.quota` middleware on `tutor.solve` and `chat.send`, counting solver + chat usage against one shared daily allowance (`SubscriptionService`).
- **Performance indexes**: `2026_08_12_120001_add_performance_indexes` covers the hot query paths (practice picks, history, quota counting, dashboard diagnostics).
