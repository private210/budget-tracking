<?php

namespace Database\Seeders;

use App\Models\BudgetAllocation;
use App\Models\Category;
use App\Models\Expense;
use App\Models\RecurringExpense;
use App\Models\Salary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;

class AugDemoSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->orderBy('id')->first();

        if (! $user) {
            return;
        }

        Auth::loginUsingId($user->id);

        $month = '2026-08'; // contoh bulan Agustus 2026

        // --- Bersihkan data lama dulu (pola sama seperti resetData) ---
        Expense::query()->delete();
        BudgetAllocation::query()->delete();
        Salary::query()->delete();
        RecurringExpense::query()->delete();

        // --- Pastikan kategori demo ada (nama+ikon+warna dari CategorySeeder) ---
        foreach (CategorySeeder::defaults() as $def) {
            Category::firstOrCreate(
                ['name' => $def['name']],
                ['icon' => $def['icon'], 'color' => $def['color'], 'is_default' => true]
            );
        }

        // --- Gaji bulan ini ---
        $salary = Salary::updateOrCreate(
            ['received_at' => $month.'-01'],
            ['amount' => 8500000, 'note' => 'Gaji Agustus 2026 (demo)']
        );

        // --- Alokasi per kategori (total 8.000.000, sisa 500.000) ---
        $plan = [
            'Kebutuhan Pokok' => 3000000,
            'Transport' => 1000000,
            'Tabungan' => 1500000,
            'Hiburan' => 600000,
            'Tagihan & Utilitas' => 1500000,
            'Lainnya' => 400000,
        ];

        $allocs = [];
        foreach ($plan as $name => $amount) {
            $category = Category::where('name', $name)->first();
            if (! $category) {
                continue;
            }
            $allocs[$name] = BudgetAllocation::updateOrCreate(
                ['salary_id' => $salary->id, 'category_id' => $category->id],
                ['amount' => $amount]
            );
        }

        // --- Pengeluaran: [tanggal, kategori, deskripsi, jumlah] ---
        $spends = [
            ['01', 'Kebutuhan Pokok', 'Belanja pasar mingguan', 250000],
            ['01', 'Transport', 'Isi bensin', 150000],
            ['02', 'Transport', 'Gojek kerja', 25000],
            ['03', 'Hiburan', 'Netflix bulanan', 75000],
            ['04', 'Lainnya', 'Cucat potong rambut', 50000],
            ['05', 'Kebutuhan Pokok', 'Belanja pasar', 280000],
            ['05', 'Tagihan & Utilitas', 'Pulsa & paket data', 100000],
            ['06', 'Transport', 'Bensin', 120000],
            ['07', 'Hiburan', 'Nonton bioskop', 90000],
            ['08', 'Kebutuhan Pokok', 'Belanja mingguan', 260000],
            ['09', 'Lainnya', 'Perlengkapan rumah', 75000],
            ['10', 'Tagihan & Utilitas', 'Listrik & air PLN', 350000],
            ['11', 'Transport', 'Isi bahan bakar', 210000],
            ['12', 'Kebutuhan Pokok', 'Belanja pasar mingguan', 310000],
            ['13', 'Hiburan', 'Makan di luar', 130000],
            ['14', 'Kebutuhan Pokok', 'Belanja kebutuhan dapur', 175000],
            ['15', 'Tagihan & Utilitas', 'Internet bulanan', 150000],
            ['16', 'Transport', 'Parkir & tol', 60000],
            ['17', 'Kebutuhan Pokok', 'Belanja mingguan 3', 330000],
            ['18', 'Hiburan', 'Game online & top up', 100000],
            ['19', 'Tagihan & Utilitas', 'Cicilan gadget', 450000],
            ['20', 'Kebutuhan Pokok', 'Belanja pasar', 295000],
            ['21', 'Transport', 'Isi ulang bensin', 175000],
            ['22', 'Tagihan & Utilitas', 'Listrik token YM', 200000],
            ['23', 'Kebutuhan Pokok', 'Belanja harian', 140000],
            ['24', 'Hiburan', 'Mini-liburan', 250000],
            ['25', 'Kebutuhan Pokok', 'Belanja mingguan akhir', 320000],
            ['26', 'Transport', 'Ojek harian', 45000],
            ['27', 'Kebutuhan Pokok', 'Belanja dapur', 160000],
            ['28', 'Tagihan & Utilitas', 'Internet fiber', 300000],
            ['29', 'Lainnya', 'Perawatan kuku', 60000],
            ['31', 'Kebutuhan Pokok', 'Belanja penutup bulan', 270000],
        ];

        $count = 0;
        foreach ($spends as [$day, $categoryName, $desc, $amount]) {
            $alloc = $allocs[$categoryName] ?? null;
            $cat = Category::where('name', $categoryName)->first();
            if (! $cat) {
                continue;
            }

            Expense::create([
                'category_id' => $cat->id,
                'budget_allocation_id' => $alloc ? $alloc->id : null,
                'amount' => $amount,
                'description' => $desc,
                'spent_at' => $month.'-'.$day,
                'is_recurring' => false,
            ]);

            if ($alloc) {
                $alloc->increment('spent', $amount);
            }
            $count++;
        }

        // --- Tagihan berulang (recurring) ---
        $recurring = [
            ['Kebutuhan Pokok', 'Belanja bulanan', 1500000, 'monthly', '05'],
            ['Tagihan & Utilitas', 'Listrik & internet', 500000, 'monthly', '10'],
            ['Tagihan & Utilitas', 'Internet fiber', 300000, 'monthly', '15'],
            ['Tagihan & Utilitas', 'BPJS Kesehatan', 210000, 'monthly', '20'],
            ['Hiburan', 'Streaming (Netflix+Spotify)', 200000, 'monthly', '25'],
            ['Transport', 'Bensin mingguan', 200000, 'weekly', '07'],
            ['Lainnya', 'Cicilan gadget', 450000, 'monthly', '19'],
        ];

        foreach ($recurring as [$categoryName, $name, $amount, $freq, $dueDay]) {
            $cat = Category::where('name', $categoryName)->first();
            if (! $cat) {
                continue;
            }

            RecurringExpense::updateOrCreate(
                ['category_id' => $cat->id, 'name' => $name],
                [
                    'amount' => $amount,
                    'frequency' => $freq,
                    'next_due_date' => $month.'-'.$dueDay,
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Data demo Agustus 2026 berhasil dibuat: gaji Rp8.500.000 + '.$count.' pengeluaran + 7 tagihan berulang.');
    }
}
