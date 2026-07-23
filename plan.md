# Desmos AI — Loyiha rejasi va bajarilgan ishlar

> Bu fayl loyihaning umumiy holatini kuzatib borish uchun. Har bir band bajarilganda `[x]` bilan belgilanadi.

---

## ✅ 1. Loyiha nomini "Desmos AI" ga o'zgartirish — BAJARILDI

- `composer.json` → `name`, `description`
- `package.json` → `name`
- `package-lock.json` → npm orqali qayta generatsiya qilindi
- `.env.example` → `APP_NAME`, `DB_DATABASE`, `CACHE_PREFIX`
- Barcha blade fayllar: sarlavhalar, logo alt-matnlari, footer, localStorage kalitlari (`resources/views/auth/login.blade.php`, `dashboard/index.blade.php`, `layouts/app.blade.php`, `layouts/dashboard.blade.php`, `errors/*.blade.php`)

---

## ✅ 2. Desmos AI MVP — Phase 1: Fundament — BAJARILDI

Batafsil texnik reja: `C:\Users\Iqbolshoh\.claude\plans\serene-munching-aho.md`

### Baza (DB)
- 12 ta yangi migratsiya (`database/migrations/2026_07_22_*`): `topics`, `student_profiles`, `questions`, `diagnostic_results`, `question_attempts`, `ai_tutor_sessions`, `saved_graphs`, `chat_threads`, `chat_messages`, `roadmaps`, `achievements`, `user_achievements`
- Mos Eloquent modellar (`app/Models/`): `Topic`, `StudentProfile`, `Question`, `DiagnosticResult`, `QuestionAttempt`, `AiTutorSession`, `SavedGraph`, `ChatThread`, `ChatMessage`, `Roadmap`, `Achievement`, `UserAchievement`
- `User.php` ga `studentProfile()`, `questionAttempts()`, `diagnosticResults()` relatsiyalari qo'shildi
- `database/seeders/RolePermissionSeeder.php`: `student` roli, yangi ruxsatlar (`questions.*`, `reports.view`, `analytics.view`), demo talaba (`student@desmosai.test` / `B7654321`, 420→800 ball)
- `database/seeders/TopicSeeder.php` (yangi): 7 ta SAT mavzusi (Heart of Algebra, Advanced Math, Problem Solving, Geometry, Trigonometry, Functions, Statistics)

### Gold tema
- `resources/css/theme.css`: `--gold`, `--gold-hover`, `--gold-deep`, `--gold-alt`, `--gold-soft`, `--gold-border`, `--gold-glow` (dark + light rejim)
- `.btn-gold`, `.badge-gold` klasslari (`layouts/dashboard.blade.php`, `layouts/marketing.blade.php`)

### Landing page
- `app/Http/Controllers/MarketingController.php`
- `resources/views/layouts/marketing.blade.php` (public nav + footer)
- `resources/views/marketing/landing.blade.php` + partiallar: `hero`, `stats`, `features`, `testimonials`, `pricing`, `faq`
- `/` route: mehmonlarga landing, login qilganlarga dashboard

### To'liq auth
- `app/Http/Controllers/RegisterController.php` + `resources/views/auth/register.blade.php` — `student` rolini va `student_profiles` yozuvini avtomatik yaratadi
- `app/Http/Controllers/PasswordResetController.php` + `resources/views/auth/{forgot-password,reset-password}.blade.php`
- `login.blade.php`: nofaol "Google bilan kirish (tez orada)" tugmasi, forgot-password/register havolalari
- `routes/web.php`: `/register`, `/forgot-password`, `/reset-password/{token}` route'lari

### Dashboard gamifikatsiya
- `DashboardController`: `studentProfile`, `recentAttempts`, `latestDiagnostic` yuklanadi
- `resources/views/dashboard/partials/`: `xp-widget`, `streak-widget`, `goal-widget`, `recent-activity`, `weak-strong-topics` — faqat `student_profiles` yozuvi bor foydalanuvchilarga ko'rinadi, admin panelga tegilmagan

### Qo'shimcha (infratuzilma)
- `storage:link` ishga tushirildi
- `ProfileController` + `profile/index.blade.php`: avatar yuklash logikasi qo'shildi (`student_profiles.avatar_path`)

### Tekshirildi
- `php artisan migrate:fresh --seed` — xatosiz
- Server ishga tushirilib, curl orqali sinaldi: landing/login/register/forgot-password 200, ro'yxatdan o'tish oqimi oxirigacha (foydalanuvchi → student roli → dashboard vidjetlari), mavjud admin sahifalar (`/users`, `/roles`, `/profile`) buzilmagan, log faylida yangi xato yo'q

---

## ⏳ 3. Phase 2 — AI Tutor + Desmos grafik + Tarix — KEYINGI NAVBATDA

- `app/Services/AiTutor/Contracts/AiTutorServiceInterface.php` — `solve()`, `chatReply()`
- DTO'lar (`app/Services/AiTutor/DTO/`): `SolveRequestDTO`, `TutorStepDTO`, `TutorResponseDTO`, `ChatRequestDTO`
- `app/Services/AiTutor/MockAiTutorService.php` — kalit so'z asosida demo javoblar (kvadrat tenglama, chiziq, aylana, statistika); haqiqiy OpenAI ulanmaydi (foydalanuvchi so'rovi bo'yicha, pullik bo'lgani uchun)
- `app/Providers/AiTutorServiceProvider.php` — `config('services.ai_tutor.driver')` orqali `mock`/`openai` almashtiriladigan qilib quriladi
- `config/services.php` + `.env.example`: `AI_TUTOR_DRIVER`, `DESMOS_API_KEY`, `OPENAI_API_KEY` (rezerv)
- `resources/views/components/desmos-calculator.blade.php` — Desmos Graphing Calculator JS API bilan qayta ishlatiladigan komponent
- `app/Http/Controllers/TutorController.php` (index/solve/show) — matn yoki rasm yuklash, `ai_tutor_sessions`ga saqlash
- `resources/views/tutor/{index,show}.blade.php` — Step 1/2/3/Final Answer UI + "nima uchun shu formula" / "SAT'da qanday yechiladi" follow-up
- `app/Http/Controllers/HistoryController.php` + `resources/views/history/{index,show}.blade.php` — saqlangan savol/rasm/AI javob/grafiklar, "Grafikni saqlash" (`SavedGraph`)
- Sidebar'ga Tutor/Tarix havolalari qo'shiladi

## ⏳ 4. Phase 3 — Diagnostika testi + Roadmap

- `database/seeders/QuestionSeeder.php` — ~20 ta diagnostik savol (4 domain bo'yicha)
- `app/Services/Diagnostic/DiagnosticService.php` + `app/Http/Controllers/DiagnosticController.php` (start/show/submit/results)
- `resources/views/diagnostic/{start,show,results}.blade.php` — Overall/Algebra/Functions/Geometry/Statistics breakdown, zaiflik tahlili
- `app/Services/Roadmap/RoadmapService.php` + `app/Http/Controllers/RoadmapController.php` (show/generate)
- `resources/views/roadmap/show.blade.php` — joriy→maqsad ball, haftalik/oylik reja, bajarilish foizi
- `app/Services/Gamification/GamificationService.php` + `config/gamification.php` — XP/streak/daraja hisoblash (birinchi marta shu fazada ishga tushadi)
- Sidebar'ga Diagnostika/Roadmap havolalari

## ⏳ 5. Phase 4 — Practice bo'limi + Chat Tutor

- `QuestionSeeder` to'liq practice bank bilan kengaytiriladi (7 mavzu × easy/medium/hard)
- `app/Http/Controllers/PracticeController.php` (index/topic/submit) + `resources/views/practice/{index,topic,quiz}.blade.php`
- `app/Http/Controllers/ChatController.php` (index/send, `AiTutorServiceInterface::chatReply` orqali) + `resources/views/chat/index.blade.php` — ChatGPT uslubidagi oyna
- Sidebar'ga Practice/Chat havolalari

## ⏳ 6. Phase 5 — Gamifikatsiya polirovka + Admin kengaytmalari

- `database/seeders/AchievementSeeder.php`
- `app/Http/Controllers/LeaderboardController.php` + `resources/views/leaderboard/index.blade.php` (demo)
- `ProfileController@achievements` + `resources/views/profile/achievements.blade.php`
- Admin uchun (mavjud `admin/users` patternida): `app/Http/Controllers/QuestionController.php` + `resources/views/admin/questions/{index,create,edit}.blade.php`
- `ReportController` + `admin/reports/index.blade.php`
- `AnalyticsController` + `admin/analytics/index.blade.php`
- `SystemStatusController` + `admin/system-status/index.blade.php`
- Sidebar'ga "Admin" bo'limi (`@canany(['questions.view','reports.view','analytics.view'])`)

---

## Eslatmalar
- AI qismi hozircha **mock/demo javoblar** bilan ishlaydi (haqiqiy OpenAI pullik bo'lgani uchun). Arxitektura `AiTutorServiceInterface` orqali keyin real providerga osongina o'tkaziladi.
- Google Login — faqat login sahifasida nofaol "tez orada" tugma, Socialite ulanmagan.
- Gold rang faqat landing page va gamifikatsiya elementlarida; admin panel ko'k `--accent`da qoladi.
- Barcha sahifalar o'zbek tilida.
- Har fazadan keyin foydalanuvchi bilan qayta ko'rib chiqiladi va tasdiqlanadi.
