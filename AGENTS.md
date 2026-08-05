# AGENTS.md

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
npm run build            # Optional: produces public/build (deploy copies it; no view uses @vite, UI runs on CDN)
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
- Clock in nav bar via JavaScript in the layout

## Loading Bar & Page Transitions

- Loading bar HTML (`#loading-bar` with `.bar` span) is the last child of `<nav>` in `layouts/app.blade.php`, positioned `bottom: 0`
- JS in layout: link clicks → `sessionStorage.np=1` + `showLoading()`; form submits → disable submit buttons + `showLoading()`; `beforeunload` → `sessionStorage.np=1` (covers F5/back-forward); on load checks `sessionStorage.np` then `hideLoading()` on `DOMContentLoaded`
- Confirm modal OK button and `confirmCancel(url)` call `showLoading()` before navigating
- `animateFilterAndSubmit(el)` (expenses) calls `showLoading()` before `el.form.submit()`
- **Invariant: any new programmatic `form.submit()` or `window.location` navigation must call `showLoading()` first (or go through the confirm modal), or the loading bar won't show.**

## Deployment (InfinityFree)

InfinityFree free plan **does not** allow changing Document Root. The deploy script handles this:

```powershell
powershell -File scripts/deploy.ps1
```

Creates `dist/` with `index.php` and `.htaccess` at root level (no `public/` subfolder), `build/` Vite assets at root, `.env.production` included, no `public/`, `tests/`, `node_modules/`, `.git/`.

### After uploading to InfinityFree

1. Rename `.env.production` → `.env`, fill MySQL credentials from cPanel
2. Visit `https://domain.com/migrate` (one-time web migration route in `routes/web.php`)
3. Delete `/migrate` route from `routes/web.php`

### Schema

- `categories` — name, icon, color, is_default
- `salaries` — amount, received_at (unique), note
- `budget_allocations` — salary_id, category_id (unique pair), amount, spent
- `expenses` — category_id, budget_allocation_id (nullable), amount, description, spent_at, is_recurring
- `recurring_expenses` — category_id, name, amount, frequency (weekly/monthly/yearly), next_due_date, is_active

## Structure

- `app/Models/` — Eloquent models (Salary, Expense, RecurringExpense, BudgetAllocation, Category)
- `app/Http/Controllers/` — Controllers (Dashboard, Budget, Expense, RecurringExpense, Report)
- `routes/web.php` — Web routes (no auth middleware)
- `resources/views/` — Blade templates (layouts/app, dashboard, budget, expenses, recurring, reports)
- `database/` — Migrations, factories, seeders

## Notes

- `.env` is gitignored; `.env.example` and `.env.production` (deploy template) are tracked
- `vendor/` is gitignored
- No CI/CD, no PHPStan/Psalm — only Pint for style
- No auth scaffolding — all routes are publicly accessible
