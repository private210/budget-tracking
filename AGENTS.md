# AGENTS.md

## Project

Laravel 11 app (PHP ^8.2) for budget tracking. Tailwind CSS via CDN (no build step for CSS).

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
npm run dev              # Vite frontend (HMR)
```

## Testing

```bash
vendor/bin/phpunit                          # All tests
vendor/bin/phpunit tests/Feature/ExampleTest.php  # Single file
vendor/bin/phpunit --filter test_name       # Single test
```

PHPUnit config: `phpunit.xml` (Unit + Feature suites, SQLite in-memory for testing).

## Code Style

```bash
vendor/bin/pint          # Auto-fix (Laravel Pint, default Laravel preset)
vendor/bin/pint --test   # Dry run
```

4-space indent, UTF-8, LF line endings (see `.editorconfig`).

## Database

Default: SQLite (`database/database.sqlite`). Migrations in `database/migrations/`.

## Loading Bar & Page Transitions

- Loading bar HTML (`#loading-bar` with `.bar` span) injected as last child of `<nav>` (main navbar) in `layouts/app.blade.php`, positioned at `bottom: 0` so it sits under the navbar
- CSS animation (smooth scrolling feel):
  - `.active` → bar fades in (opacity 0→1 over 250ms), width grows 0→35% (cubic-bezier 400ms), then gradient shimmer slides (`.barShimmer` 0.8s linear) + `hueRotate` cycles hues smoothly (2.5s linear) for continuous rainbow transition
  - `.complete` → bar fills to 100% (300ms cubic-bezier), then fades out (350ms after 250ms delay)
- JS interceptors in layout's `<script>`:
  - **Link clicks**: internal `<a>` tags set `sessionStorage.np=1` + `showLoading()`
  - **Form submits**: disable all submit buttons + `showLoading()` + set flag
  - **Confirm OK (modal)**: calls `showLoading()` before callback
  - **`confirmCancel(url)`**: calls `showLoading()` inside `onConfirm` callback before `window.location.href=url`
  - **`animateFilterAndSubmit`** (expenses): calls `showLoading()` before `el.form.submit()`
- On page load: checks `sessionStorage.np` → `showLoading()` → `hideLoading()` on `DOMContentLoaded`
- `beforeunload` handler sets `sessionStorage.np=1` so page refreshes (F5) and back/forward navigation also trigger the loading bar on next load
- `hideLoading()` adds `.complete` → bar smoothly fills to 100% & fades out (even if triggered during entrance, transition picks up from current width)
- All instances of programmatic `form.submit()` are covered either by confirm modal (which pre-shows loading) or by direct `showLoading()` calls

### Schema

- `categories` — name, icon, color, is_default
- `salaries` — amount, received_at (unique), note
- `budget_allocations` — salary_id, category_id, amount, spent
- `expenses` — category_id, budget_allocation_id (nullable), amount, description, spent_at, is_recurring
- `recurring_expenses` — category_id, name, amount, frequency, next_due_date, is_active

## Structure

- `app/Models/` — Eloquent models (Salary, Expense, RecurringExpense, BudgetAllocation, Category)
- `app/Http/Controllers/` — Controllers (Dashboard, Budget, Expense, RecurringExpense, Report)
- `routes/web.php` — Web routes (no auth middleware)
- `resources/views/` — Blade templates (layouts/app, dashboard, budget, expenses, recurring, reports)
- `config/` — Config files
- `database/` — Migrations, factories, seeders

## Key Patterns

- All salary queries filter by current month's `received_at` range
- `$salary` can be null when no salary is recorded for the current month — views must use null-safe operators (`?->`)
- Clock in nav bar via JavaScript (`layouts/app.blade.php`)
- Tailwind CSS loaded from CDN (no npm build required for styling)
- **No dark mode** — theme toggle & brand color picker have been removed for performance
- **No anime.js** — animation library removed; Chart.js only on reports page
- All views are light-mode only (no `dark:` classes)

## Notes

- `.env` is gitignored; `.env.example` is tracked
- `vendor/` is gitignored
- No CI/CD configured yet
- No PHPStan/Psalm — only Pint for style
- No auth scaffolding — all routes are publicly accessible
