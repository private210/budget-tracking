<?php

namespace App\Http\Controllers;

use App\Models\BudgetAllocation;
use App\Models\Expense;
use App\Models\RecurringExpense;
use App\Models\Salary;

class DashboardController extends Controller
{
    public function index()
    {
        $currentMonth = now()->startOfMonth();
        $salary = Salary::where('received_at', '>=', $currentMonth)
            ->where('received_at', '<=', now()->endOfMonth())
            ->first();

        $allocations = $salary ? $salary->budgetAllocations()->with('category')->get() : collect();
        $totalAllocated = $salary ? $salary->totalAllocated() : 0;
        $totalSpent = $salary ? $salary->totalSpent() : 0;
        $remaining = $salary ? $salary->remaining() : 0;

        $recentExpenses = Expense::with('category')
            ->where('spent_at', '>=', $currentMonth)
            ->latest('spent_at')
            ->limit(10)
            ->get();

        $monthlyTotal = Expense::where('spent_at', '>=', $currentMonth)->sum('amount');

        $dueRecurring = RecurringExpense::with('category')
            ->where('is_active', true)
            ->where('next_due_date', '<=', now())
            ->get();

        $greetingName = strtok(auth()->user()->name, ' ');
        $hour = (int) now()->format('G');
        $timeGreeting = $hour < 11 ? 'Selamat pagi' : ($hour < 15 ? 'Selamat siang' : ($hour < 19 ? 'Selamat sore' : 'Selamat malam'));
        $quotes = [
            'Setiap rupiah yang kamu hemat hari ini adalah investasi untuk masa depanmu.',
            'Kebebasan finansial dimulai dari keputusan kecil yang konsisten.',
            'Daripada menunggu cukup, mulai kelola dari yang ada sekarang.',
            'Anggaran yang jelas adalah peta menuju tujuan keuanganmu.',
            'Disiplin hari ini adalah kemapanan esok hari.',
            'Setiap pengeluaran tercatat adalah langkah menuju pengelolaan keuangan yang sehat.',
            'Kekayaan sejati datang dari kebiasaan, bukan dari jumlah.',
            'Rencanakan dengan bijak agar bulan depannya terasa lebih ringan.',
            'Mengatur keuangan adalah bentuk cinta untuk masa depanmu.',
        ];
        $motivation = $quotes[array_rand($quotes)];

        return view('dashboard', compact(
            'salary',
            'allocations',
            'totalAllocated',
            'totalSpent',
            'remaining',
            'recentExpenses',
            'monthlyTotal',
            'dueRecurring',
            'greetingName',
            'timeGreeting',
            'motivation'
        ));
    }

    public function resetData()
    {
        RecurringExpense::query()->delete();
        Expense::query()->delete();
        BudgetAllocation::query()->delete();
        Salary::query()->delete();

        return back()->with('success', 'Semua data berhasil direset. Kategori tetap tersimpan.');
    }

    public function datetime()
    {
        app()->setLocale('id');
        $now = now();

        return response()->json([
            'weekday' => $now->translatedFormat('l'),
            'day' => (int) $now->format('j'),
            'month' => $now->translatedFormat('F'),
            'year' => (int) $now->format('Y'),
            'time' => $now->format('H:i:s'),
            'epoch' => $now->getTimestamp(),
            'monthYear' => $now->translatedFormat('F Y'),
        ]);
    }
}
