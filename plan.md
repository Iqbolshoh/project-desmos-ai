# Desmos AI — Production Upgrade (v2.1) Master Plan

> This master plan outlines the complete production upgrade of Desmos AI to version 2.1 following PSR-12, Laravel Best Practices, full English language standardization, database query optimizations, pagination, and strict security rate limiting.

---

## 🎯 Production Upgrade Checklist (v2.1)

### 1. English Language Standardization
- [ ] Translate all PHP docblocks, inline comments, variable names, and exception messages to English.
- [ ] Translate all user-facing texts, placeholders, alerts, and badges across Blade templates to English.
- [ ] Translate all seeded database records (topics, questions, achievements, hints) to English.

### 2. Version Bump to v2.1
- [ ] Update `composer.json` (`"version": "2.1.0"`).
- [ ] Update `package.json` (`"version": "2.1.0"`).
- [ ] Update brand names, footers, headers, and hero titles across all Blade layouts to `Desmos AI v2.1`.

### 3. Migrations & Seeders Optimization
- [ ] Consolidate and verify all 16 `create_*` migrations in `database/migrations/`.
- [ ] Add explicit database indexes on foreign keys and search columns (`user_id`, `topic_id`, `created_at`, `status`, `difficulty`, `is_diagnostic`).
- [ ] Synchronize all seeders in `database/seeders/` to match the exact migration schemas.

### 4. PSR-12 & Clean Code Refactoring
- [ ] Remove all inline Fully Qualified Class Names (`\App\Models\User`, `\Carbon\Carbon`, `\Str::slug`, `\Illuminate\Support\Js::from()`) across the entire codebase.
- [ ] Import all classes using `use` statements at the top of every PHP file.
- [ ] Format code cleanly according to PSR-12 standards.

### 5. Security & Rate Limiting (Throttling)
- [ ] Enforce `throttle` middleware on every route in `routes/web.php` (`throttle:60,1` for general auth routes, `throttle:15,1` for AI endpoints, `throttle:5,1` for auth forms).

### 6. Performance & N+1 Query Optimization + Pagination
- [ ] Implement `.paginate(10)` / `.paginate(15)` on all listing controllers (History, Practice, Diagnostic results, Leaderboard, Admin management).
- [ ] Implement eager loading (`with([...])`) on all queries to eliminate N+1 database calls.
