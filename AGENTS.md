# AGENTS.md

## Before Starting Work (WAJIB setiap sesi baru)

Sebelum mengerjakan apa pun, baca dulu kondisi git agar tracking & lanjutan project mudah:

```bash
git branch -v              # posisi branch (master = production, feature/google-auth = advance)
git status                 # perubahan yang belum di-commit
git log --oneline -10      # riwayat terakhir (cari commit reverted/pending)
git stash list             # pekerjaan tersimpan sementara
```

Kemudian baca bagian **Session Memory** di bawah (status deploy, branch, fitur, jebakan) sebelum mulai.

## Project

Laravel 11 (PHP ^8.2) budget tracker. Tailwind CSS via CDN (no build step for CSS).
UI is **Indonesian** (labels, category names, clock "WIB"); locale `id`, timezone `Asia/Jakarta` — match this in new views.

## Quick Start

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Dev Server

```bash
php artisan serve        # Backend (http://localhost:8000)
npm run dev              # Vite (HMR)
npm run build            # Optional: produces public/build (no view uses @vite, UI runs on CDN)
```

## Testing

```bash
vendor/bin/phpunit                              # All tests
vendor/bin/phpunit tests/Feature/ExampleTest.php  # Single file
vendor/bin/phpunit --filter test_name           # Single test
```

`phpunit.xml` does NOT configure a test DB (sqlite env lines are commented out) — tests run against the default connection (`database/database.sqlite`). No `RefreshDatabase` is used. Only scaffold ExampleTest exists.

## Code Style

```bash
vendor/bin/pint          # Auto-fix (Laravel Pint, default Laravel preset)
vendor/bin/pint --test   # Dry run
```

4-space indent, UTF-8, LF line endings (see `.editorconfig`).

## Database

Default: SQLite (`database/database.sqlite`). Migrations in `database/migrations/`. Seed `CategorySeeder` (6 default categories) after fresh migrate.

## Key Patterns

- All salary queries filter by current month's `received_at` range (`BudgetController`, `DashboardController`, `ExpenseController`, `ReportController`)
- `$salary` can be null when no salary is recorded for the current month — views must use null-safe operators (`?->`)
- **Dark mode is supported** — every view has `dark:` classes, `#theme-toggle` in navbar, `tailwind.config = { darkMode: 'class' }`. Include `dark:` variants in new views.
- JS libs via CDN in `layouts/app.blade.php`: Tailwind, **anime.js** (confirm modal + flash animations), and Chart.js (reports page only)
- Views use `mobile-card-table` (responsive card layout via `data-label`), `btn-press`, `fade-in` utility classes
- Clock: in greeting banner (pure JS, no API — see Features built)

## Loading Bar & Page Transitions

- Loading bar HTML (`#loading-bar` with `.bar` span) is the last child of `<nav>` in `layouts/app.blade.php`, positioned `bottom: 0`
- JS in layout: link clicks → `sessionStorage.np=1` + `showLoading()`; form submits → disable submit buttons + `showLoading()`; `beforeunload` → `sessionStorage.np=1` (covers F5/back-forward); on load checks `sessionStorage.np` then `hideLoading()` on `DOMContentLoaded`
- Confirm modal OK button and `confirmCancel(url)` call `showLoading()` before navigating
- `animateFilterAndSubmit(el)` (expenses) calls `showLoading()` before `el.form.submit()`
- **Invariant: any new programmatic `form.submit()` or `window.location` navigation must call `showLoading()` first (or go through the confirm modal), or the loading bar won't show.**

## Deployment (Vercel)

Production runs on **Vercel + Neon PostgreSQL** (`https://titik-simpan.vercel.app`), auto-deploy from GitHub push to `master`. No deploy script needed — no `scripts/` folder.

### Schema

- `categories` — name, icon, color, is_default
- `salaries` — amount, received_at (unique), note
- `budget_allocations` — salary_id, category_id (unique pair), amount, spent
- `expenses` — category_id, budget_allocation_id (nullable), amount, description, spent_at, is_recurring
- `recurring_expenses` — category_id, name, amount, frequency (weekly/monthly/yearly), next_due_date, is_active

## Structure

- `app/Models/` — Eloquent models (Salary, Expense, RecurringExpense, BudgetAllocation, Category)
- `app/Http/Controllers/` — Controllers (Dashboard, Budget, Expense, RecurringExpense, Report)
- `routes/web.php` — Web routes (guest group = login/register/google; `auth` group = all app routes)
- `resources/views/` — Blade templates (layouts/app, dashboard, budget, expenses, recurring, reports)
- `database/` — Migrations, factories, seeders

## Notes

- `.env` and `.env.production` are gitignored; `.env.example` and `.env.production.example` (placeholders) are tracked
- `vendor/` is gitignored
- No CI/CD, no PHPStan/Psalm — only Pint for style
- Auth: password login/register + Google OAuth (socialite); all app routes require login

## Session Memory (last updated: 2026-08-07)

### Deployment status

- **Production = Vercel + Neon PostgreSQL** (`https://titik-simpan.vercel.app`), auto-deploy from GitHub push to `master`
- **Auth LIVE** since merge `f1d51e6` (2026-08-06): all routes require login/register; Google OAuth + `/profile` deployed but **Google login disabled until env vars set** (`AuthController` shows "Login Google belum dikonfigurasi" when `GOOGLE_CLIENT_ID` empty — password auth still works)
- **TODO (manual, user must do):** (1) update OAuth credentials at console.cloud.google.com with redirect URI `https://titik-simpan.vercel.app/auth/google/callback`; (2) update `APP_URL` + `GOOGLE_REDIRECT_URI` to `https://titik-simpan.vercel.app` on Vercel env (domain changed from budget-tracking-inky); (3) run google migration on Neon (migration not auto-run on Vercel): in Neon SQL editor run `ALTER TABLE users ADD COLUMN google_id varchar(255) NULL UNIQUE; ALTER TABLE users ADD COLUMN avatar varchar(255) NULL;`
- Env vars on Vercel dashboard (3): `DB_URL` (Neon **direct host** `ep-shy-recipe-azknc5gk.c-3.ap-southeast-1.aws.neon.tech:5432`, NOT the `-pooler` host), `APP_KEY` (cari aslinya di Vercel dashboard / `.env.production` lokal), `APP_URL=https://titik-simpan.vercel.app`
- `vercel.json` sets serverless env: `SESSION_DRIVER=cookie`, `SESSION_SECURE_COOKIE=true`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `LOG_CHANNEL=stderr`, cache paths `/tmp`, `DB_CONNECTION=pgsql`, `DB_SSLMODE=require`
- `config/database.php` pgsql has `'port' => '5432'` hardcoded (legacy `DB_PORT=3306` from MySQL template caused Neon timeouts)
- All form actions/JS URLs use **relative routes** `route('name', [], false)` — never absolute (wrong APP_URL caused browser "form tidak aman" warnings)
- **`.env.production` GITIGNORED sejak 2026-08-07 (commit cleanup) — jangan pernah commit nilai asli lagi.** Pola = `.env.production.example` (placeholder). Neon password pernah terekspos di chat & mungkin sudah di-reset — ambil nilai asli dari Vercel dashboard, jangan commit.

### Git branches — IMPORTANT

- **`master`** = production code WITH auth (login/register required, Google OAuth, /profile). Pushed as `f1d51e6` (merge `feature/google-auth`).
- **`feature/google-auth`** = merged into master & pushed. Work tree clean. Todos are now on Vercel/Google dashboards, not code.
  - Google OAuth login (laravel/socialite), halaman profil `/profile` + avatar dropdown di navbar, migration `google_id`+`avatar` di users
  - Full security phase (auth middleware, login/register, rate limit, security headers)
- Remaining manual steps (Vercel/Google): update `APP_URL` + `GOOGLE_REDIRECT_URI` to `https://titik-simpan.vercel.app` on Vercel env, update OAuth credentials at console.cloud.google.com (redirect URI `https://titik-simpan.vercel.app/auth/google/callback`), run google migration SQL on Neon (see Deployment status)
- Local `.env` already has empty `GOOGLE_*` keys; local sqlite already migrated with google columns

### Features built (all on master)

- Categories CRUD: `/categories` menu (navbar + bottom nav), tambah/edit/hapus kategori (nama, ikon emoji, warna hex divalidasi `regex:/^#[0-9A-Fa-f]{6}$/`), blok hapus bila masih punya pengeluaran — `CategoryController` + `categories/index.blade.php`
- Reset data total: tombol merah "Reset Data" di kanan atas Dashboard → modal wajib ketik `HAPUS` (`requireText` option pada confirm modal di layout) → POST `/reset-data` (`DashboardController::resetData`, hapus recurring → expenses → allocations → salaries, kategori tetap)
- Budget: form alokasi tersembunyi di balik tombol "+ Alokasikan Dana" (`toggleAllocForm`, `#alloc-form` awal `hidden`)
- Loading bar: satu `.bar` dengan **gradient rainbow 7-warna beranimasi** (`@keyframes loading-shift` 4s linear infinite, `background-size: 200%`) — segmen rainbow diminta user kembali (meski brand hijau), box-shadow indigo; progres JS naik acak ke max 90% lalu 100%
- Count-up animation: `animateNumbers()` scan `[data-count]`, anime.js 0→target, format `Rp X` (id-ID)
- Filter pengeluaran periode `from`/`to` bulan (swap jika `to<from`), `$totalPeriod` di `ExpenseController::index`
- Logo square & rounded: auth + navbar icon imgs pakai kotak `object-contain` (bukan potrait) agar background terlihat persegi
- Greeting dashboard: **server-rendered** di `DashboardController::index` (sapaan waktu + nama depan + kutipan acak), not API — kartu hijau brand `#1BA37A` + shadow, tanpa placeholder & tanpa delay saat refresh. Bukannya `/greeting` (sudah dihapus)
- Theme: 3 mode `light`/`dark`/`auto` (ikut `prefers-color-scheme` OS, listen `change` + `localStorage.theme`; default `auto`) — **dropdown menu** (Terang/Gelap/Sistem, `#theme-menu` + `selectTheme()`/`toggleThemeMenu()`), dipakai di layout & halaman auth. Toggle lama (cycle button) sudah diganti dropdown
- Font: body Nunito (regular), `.font-brand` + `.font-bold/.font-extrabold` = Poppins (bold), `.font-slogan` = Nunito SemiBold — Google Fonts `Poppins:wght@600;700` + `Nunito:wght@400;600` di layout & auth
- Clock: **tidak lagi di navbar** — sekarang di dalam greeting banner dashboard (`#greeting-clock`, hari + tanggal + jam **WIB**, update per detik). **Posisi** (2026-08-07, `acafa17`): di desktop jam muncul **kanan** banner (div `md:ml-auto`, wrap), di mobile tetap di bawah teks (kolom `w-full` + `mt-2`). **Revisi (2026-08-07, setelah `1940daf`)**: kembali **pure JS tanpa API** — `days`/`months` array + `new Date()` di `dashboard.blade.php`, pakai `pad()` + jam lokal (bukan `toLocaleTimeString` yang memberi titik & rawan RangeError). API `/api/datetime` + method `DashboardController::datetime` **dihapus**. `#dashboard-month` juga di-set dari array bulan.
- Brand colors (2026-08-07, `acafa17`): semua warna default indigo diganti ke brand identity — primary `#1BA37A` (hover `#0F8F68`, active `#0C7A59`), dark accent `#6EE7B0`, tint `bg-[#1BA37A]/10`, mint `#BDE0D2`, `#88B239`, amber `#FDA61C`, `#1F3A56` (navy). Loading bar gradient jadi brand-green (bukan rainbow indigo). Jangan gunakan `indigo`/`#4f46e5`/`#818cf8` lagi.

### Security state (production)

- Kept: `SecurityHeaders` middleware (X-Frame-Options DENY, nosniff, Referrer-Policy, Permissions-Policy), reports chart data uses `json_encode(..., JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT)` (stored XSS via category names), all POST forms `@csrf`
- Active on production: auth middleware (login/register/logout required), rate limiter `throttle:login` (10/min per IP), `redirectGuestsTo('/login')`
- `composer audit`: Guzzle patched to 7.15.2 (CVE-2026-69246, high) — on feature branch; Laravel 11 email-rule CRLF advisory (CVE-2026-48019) has **no 11.x patch** — low impact, app sends no email
- Old debugging routes `/debug` and `/migrate` were **deleted** (were temporary for Vercel/Neon diagnostics) — do not re-add

### Known pitfalls

- Neon **pooler** host fails multi-statement transactions with `SQLSTATE[25P02] current transaction is aborted` (Laravel prepared statements vs transaction-mode pooler) — always use the direct host
- **Clock JS** (2026-08-07, commit cleanup): jangan pakai `toLocaleTimeString('id-ID', ...)` untuk jam WIB — locale `id` memberi format titik (`16.30.05`) & rawan RangeError tz di sebagian browser. Pakai pola deterministik: `pad()` + jam lokal `new Date()` (2026-08-08, revert API → pure JS)
- **Export PDF/Excel** (2026-08-08): `ReportController::export($format)` di route `reports.export` (`GET /reports/export/{format}`, where `pdf|xlsx`) — pakai `barryvdh/laravel-dompdf` (view `reports/pdf.blade.php`) + `phpoffice/phpspreadsheet` (stream download). Periode = query `month` (default bulan ini). Baca `month` pakai `$request->query()` — `Request::get()` deprecated di symfony 7.4. Jangan lupa `app()->setLocale('id')` di awal method agar nama bulan Indonesian (local locale `en`).
- **Seeder demo** (2026-08-08): `AugDemoSeeder` (`database/seeders/`) — isi gaji Rp8.500.000 + alokasi 6 kategori + 32 pengeluaran Agustus + 7 tagihan berulang; idempotent (bersihkan data lama dulu, pola resetData). Jalankan lokal: `php artisan db:seed --class=AugDemoSeeder`. **Di Vercel**: pakai route `GET /seed-demo?token=<DEMO_SEED_TOKEN>` (route `demo.seed` di `routes/web.php`) yang memanggil `Artisan::call('db:seed')` — Vercel tidak punya SSH/artisan. Route ditutup token env `DEMO_SEED_TOKEN` (403/hilang jika env kosong) — set di Vercel dashboard sebelum panggil, hapus env setelah selesai.
- Local PHP has NO `pdo_pgsql` — can't test Neon from local; test DB is sqlite
- Local smoke test pattern: `Start-Process php -ArgumentList "artisan","serve","--port=80XX"` + `Invoke-WebRequest ... -UseBasicParsing` (PS 5.1 needs `-UseBasicParsing`; redirects need `-MaximumRedirection 0 -ErrorAction SilentlyContinue` and reading `$_.Exception.Response.Headers.Location`)
- Vercel cold start is slow (free plan) — loading bar exists for this reason
- **Static files in `public/` do NOT auto-serve on Vercel** — the `vercel.json` catch-all `/(.*)` → `/api/index.php` sends every request to Laravel. Any new `public/` asset must get a route in `routes/web.php` (see the `$asset` loop near the top). `logo.svg` worked only because it had an explicit route. If an image shows alt-text on production but works locally, it's a missing asset route. **Assets are: `logo.svg`, `darkmode-logo.svg`, `favicon.ico`, `icon-light.svg`, `icon-dark.svg`, `icon-monokrom.svg`** (icons are `icon-*` kebab-case, no spaces — auth pages use light/dark variants, navbar uses monokrom). `favicon.ico` is a real ICO (256px PNG payload) — regenerate by screenshotting SVG via headless Edge + wrapping PNG in ICO header.

