<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Expense;
use App\Models\RecurringExpense;
use Illuminate\Http\Request;

class RecurringExpenseController extends Controller
{
    public function index()
    {
        $recurringExpenses = RecurringExpense::with('category')
            ->orderBy('next_due_date')
            ->get();

        return view('recurring.index', compact('recurringExpenses'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('recurring.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0.01',
            'frequency' => 'required|in:weekly,monthly,yearly',
            'next_due_date' => 'required|date',
        ]);

        RecurringExpense::create($validated);

        return redirect()->route('recurring.index')
            ->with('success', 'Pengeluaran berulang berhasil ditambahkan!');
    }

    public function update(RecurringExpense $recurringExpense, Request $request)
    {
        $validated = $request->validate([
            'is_active' => 'required|boolean',
        ]);

        $recurringExpense->update($validated);

        return back()->with('success', 'Status berhasil diperbarui!');
    }

    public function destroy(RecurringExpense $recurringExpense)
    {
        $recurringExpense->delete();

        return back()->with('success', 'Pengeluaran berulang berhasil dihapus!');
    }

    public function markPaid(RecurringExpense $recurringExpense)
    {
        Expense::create([
            'category_id' => $recurringExpense->category_id,
            'amount' => $recurringExpense->amount,
            'description' => $recurringExpense->name,
            'spent_at' => now(),
            'is_recurring' => true,
        ]);

        $recurringExpense->markAsPaid();

        return back()->with('success', 'Pembayaran tercatat!');
    }
}
