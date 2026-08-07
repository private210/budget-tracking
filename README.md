# Titik Simpan — Budget Tracker

Aplikasi pencatat keuangan pribadi berbasis web. Kelola gaji bulanan, alokasikan dana per kategori, catat pengeluaran, pantau sisa saldo, dan buat pengeluaran berulang — semua dalam satu dashboard dengan dukungan tema gelap/terang.

Live demo: [https://titik-simpan.vercel.app](https://titik-simpan.vercel.app)

---

## Daftar Isi

- [Spesifikasi Teknologi](#spesifikasi-teknologi)
- [Fitur](#fitur)
- [Struktur Proyek](#struktur-proyek)
- [Instalasi Lokal](#instalasi-lokal)
- [Menjalankan](#menjalankan)
- [Penggunaan](#penggunaan)
- [Testing & Code Style](#testing--code-style)
- [Deployment (Vercel + Neon)](#deployment-vercel--neon)
- [Keamanan](#keamanan)
- [Lisensi](#lisensi)

---

## Spesifikasi Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel 11 (PHP ^8.2) |
| Database | SQLite (lokal), PostgreSQL (produksi — Neon) |
| Frontend | Blade templates + Tailwind CSS (CDN, tanpa build step untuk CSS) |
| JavaScript | Vanilla JS + anime.js (animasi), Chart.js (grafik laporan) |
| Autentikasi | Password (session) + Google OAuth (laravel/socialite) |
| Deployment | Vercel (serverless PHP) + Neon PostgreSQL, auto-deploy dari push `master` |
| Lokalisasi | Bahasa Indonesia (`locale=id`), zona waktu `Asia/Jakarta` (WIB) |
| Tema | Light / Dark / Auto (ikut sistem), disimpan di `localStorage` |

## Fitur

- **Dashboard** — sapaan dinamis, jam real-time WIB, total saldo/alokasi/tersisa, pengeluaran terbaru, alokasi mendekati batas, dan pengingat pengeluaran berulang.
- **Gaji & Alokasi** — catat gaji bulan berjalan (unik per bulan), alokasikan dana ke kategori, pantau sisa saldo per kategori.
- **Pengeluaran** — catat pengeluaran dengan kategori & keterangan, filter rentang tanggal (dua tanggal otomatis ditukar jika `to < from`), total periode.
- **Pengeluaran Berulang** — frequency mingguan/bulanan/tahunan dengan tanggal jatuh tempo.
- **Kategori** — CRUD kategori (nama, ikon emoji, warna hex), kategori default tidak bisa dihapus bila masih memiliki pengeluaran.
- **Laporan** — grafik pengeluaran per bulan dan per kategori.
- **Reset Data** — reset seluruh data keuangan (pengeluaran, alokasi, gaji, berulang) tanpa menghapus kategori, dengan konfirmasi wajib ketik `HAPUS`.
- **Profil** — lihat/ubah profil, avatar Google jika login via OAuth.
- **Tema 3 mode** — Terang/Gelap/Sistem, dropdown di navbar.
- **Antarmuka Indonesia** — semua label, kategori, dan jam menggunakan WIB.

## Struktur Proyek

```
├── app/
│   ├── Http/Controllers/     # Dashboard, Budget, Expense, RecurringExpense, Category, Report, Profile, Auth
│   ├── Http/Middleware/      # SecurityHeaders (X-Frame-Options, nosniff, Referrer-Policy, dll)
│   └── Models/               # Salary, Expense, RecurringExpense, BudgetAllocation, Category, User
├── config/                   # app, database, session, dll
├── database/
│   ├── migrations/           # Skema: categories, salaries, budget_allocations, expenses, recurring_expenses, google (users)
│   └── seeders/              # CategorySeeder (6 kategori default)
├── public/                   # Asset statis (logo, ikon, favicon) — dilayani via route khusus di Vercel
├── resources/views/          # Blade: layouts/app, dashboard, budget, expenses, recurring, categories, reports, profile, auth
├── routes/web.php            # Semua route (auth group = semua halaman aplikasi)
├── api/index.php             # Entry point serverless untuk Vercel
└── vercel.json               # Konfigurasi serverless (env SESSION_DRIVER=cookie, dll)
```

**Skema Database**

- `categories` — name, icon, color, is_default
- `salaries` — amount, received_at (unique), note
- `budget_allocations` — salary_id, category_id (unique pair), amount, spent
- `expenses` — category_id, budget_allocation_id (nullable), amount, description, spent_at, is_recurring
- `recurring_expenses` — category_id, name, amount, frequency (weekly/monthly/yearly), next_due_date, is_active
- `users` — + google_id, avatar (untuk Google OAuth)

## Instalasi Lokal

Prasyarat: PHP ^8.2, Composer, Node.js (opsional untuk build Vite).

```bash
# 1. Clone & masuk direktori
git clone <repo-url> budget-tracking
cd budget-tracking

# 2. Install dependensi
composer install
npm install          # hanya untuk tooling Vite; UI memakai CDN

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate

# 4. Siapkan database (SQLite default)
php artisan migrate
php artisan db:seed --class=CategorySeeder
```

## Menjalankan

```bash
php artisan serve        # Backend → http://localhost:8000
npm run dev              # Vite HMR (opsional)
npm run build            # Opsional: menghasilkan public/build (tidak dipakai view)
```

Buka `http://localhost:8000`, lalu daftar akun atau login.

## Penggunaan

1. **Login/Register** — buat akun; semua halaman aplikasi memerlukan autentikasi. Google login bisa diaktifkan dengan mengisi `GOOGLE_CLIENT_ID/SECRET/REDIRECT_URI`.
2. **Masukkan gaji** — dari halaman Budget ("+ Alokasikan Dana"), isi nominal gaji bulan berjalan lalu alokasikan ke kategori (mis. Makanan, Transport, Tabungan).
3. **Catat pengeluaran** — halaman Pengeluaran → Tambah; pilih kategori (yang sudah dialokasikan otomatis mengurangi sisa saldo kategori).
4. **Pantau dashboard** — sisa saldo bulanan, alokasi per kategori, pengeluaran terbaru, dan tagihan berulang yang jatuh tempo.
5. **Laporan** — lihat grafik pengeluaran bulanan/per kategori.
6. **Atur pengeluaran berulang** — untuk tagihan rutin; muncul di dashboard saat `next_due_date` tiba.
7. **Reset data** — tombol merah "Reset Data" di kanan atas Dashboard, ketik `HAPUS` untuk konfirmasi.

## Testing & Code Style

```bash
vendor/bin/phpunit                    # Semua test (SQLite default, tanpa RefreshDatabase)
vendor/bin/pint                       # Auto-fix style (Laravel preset)
vendor/bin/pint --test                # Dry run
```

## Deployment (Vercel + Neon)

Produksi berjalan di **Vercel + Neon PostgreSQL**, auto-deploy dari push ke branch `master`. Tidak ada script deploy — push saja.

### Env vars di dashboard Vercel

| Variabel | Nilai |
|---|---|
| `DB_URL` | URL Neon **direct host** (mis. `postgresql://user:pass@ep-xxx.c-3.ap-southeast-1.aws.neon.tech:5432/neondb?sslmode=require`) — jangan gunakan host `-pooler` (gagal di transaksi multi-statement) |
| `APP_KEY` | Dari `php artisan key:generate` lokal |
| `APP_URL` | `https://<nama-project>.vercel.app` |

`vercel.json` sudah menyetel env serverless: `SESSION_DRIVER=cookie`, `SESSION_SECURE_COOKIE=true`, `CACHE_STORE=array`, `QUEUE_CONNECTION=sync`, `LOG_CHANNEL=stderr`, cache di `/tmp`, `DB_CONNECTION=pgsql`, `DB_SSLMODE=require`.

### Catatan deployment

- **Migrasi tidak berjalan otomatis di Vercel** — jalankan SQL migrasi baru secara manual di console Neon.
- **Asset statis** (`public/`) tidak diserve otomatis — setiap file baru harus didaftarkan di loop `$asset` pada `routes/web.php` (`logo.svg`, `favicon.ico`, `icon-*`, `darkmode-logo.svg`).
- Cold start serverless lambat di free plan — loading bar bawaan menutupinya.

## Keamanan

- Semua route aplikasi dilindungi middleware `auth`; login di-rate-limit (`throttle:login`, 10/menit/IP).
- `SecurityHeaders` middleware: `X-Frame-Options: DENY`, `nosniff`, `Referrer-Policy`, `Permissions-Policy`.
- Semua form memakai `@csrf`; data chart laporan di-escape dengan `JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT` (anti stored XSS).
- File sensitif (`.env`, `.env.production`) ada di `.gitignore` — jangan commit nilai asli; gunakan `.env.production.example` sebagai template placeholder.

## Lisensi

Proyek pribadi — MIT.
