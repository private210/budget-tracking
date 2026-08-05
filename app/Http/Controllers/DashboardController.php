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

        return view('dashboard', compact(
            'salary',
            'allocations',
            'totalAllocated',
            'totalSpent',
            'remaining',
            'recentExpenses',
            'monthlyTotal',
            'dueRecurring'
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
}
