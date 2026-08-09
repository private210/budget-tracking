<?php

namespace App\Http\Controllers;

use App\Models\BudgetAllocation;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $from = $request->filled('from')
            ? Carbon::createFromFormat('Y-m', $request->from)->startOfMonth()
            : now()->startOfMonth();
        $to = $request->filled('to')
            ? Carbon::createFromFormat('Y-m', $request->to)->endOfMonth()
            : now()->endOfMonth();

        if ($to->lt($from)) {
            [$from, $to] = [$to->copy()->startOfMonth(), $from->copy()->endOfMonth()];
        }

        $query = Expense::with('category', 'budgetAllocation')
            ->whereBetween('spent_at', [$from, $to]);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $expenses = $query->latest('spent_at')->paginate(20);
        $categories = Category::all();

        $totalPeriod = Expense::whereBetween('spent_at', [$from, $to])->sum('amount');

        return view('expenses.index', compact('expenses', 'categories', 'totalPeriod'));
    }

    public function create()
    {
        $categories = Category::all();

        $salary = Salary::currentMonth()->first();

        $allocations = $salary
            ? $salary->budgetAllocations()->with('category')->get()
            : collect();

        return view('expenses.create', compact('categories', 'allocations'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        $expense = Expense::create($validated);

        if ($expense->budget_allocation_id) {
            $allocation = BudgetAllocation::find($expense->budget_allocation_id);
            if ($allocation) {
                $allocation->increment('spent', $expense->amount);
            }
        }

        return redirect()->route($request->input('from') === 'dashboard' ? 'dashboard' : 'expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat!');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->budget_allocation_id) {
            $allocation = BudgetAllocation::find($expense->budget_allocation_id);
            if ($allocation) {
                $allocation->decrement('spent', $expense->amount);
            }
        }

        $expense->delete();

        return back()->with('success', 'Pengeluaran berhasil dihapus!');
    }

    public function edit(Expense $expense)
    {
        $categories = Category::all();

        $salary = Salary::currentMonth()->first();

        $allocations = $salary
            ? $salary->budgetAllocations()->with('category')->get()
            : collect();

        return view('expenses.edit', compact('expense', 'categories', 'allocations'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $this->validateExpense($request);

        $oldAllocationId = $expense->budget_allocation_id;
        $oldAmount = $expense->amount;
        $newAllocationId = $validated['budget_allocation_id'] ?? null;
        $newAmount = $validated['amount'];

        if ($oldAllocationId) {
            $oldAlloc = BudgetAllocation::find($oldAllocationId);
            if ($oldAlloc) {
                $oldAlloc->decrement('spent', $oldAmount);
            }
        }

        $expense->update($validated);

        if ($newAllocationId) {
            $newAlloc = BudgetAllocation::find($newAllocationId);
            if ($newAlloc) {
                $newAlloc->increment('spent', $newAmount);
            }
        }

        return redirect()->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui!');
    }

    private function validateExpense(Request $request): array
    {
        $uid = auth()->id();

        return $request->validate([
            'category_id' => ['required', Rule::exists('categories', 'id')->where('user_id', $uid)],
            'budget_allocation_id' => ['nullable', Rule::exists('budget_allocations', 'id')->where('user_id', $uid)],
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
            'spent_at' => 'required|date',
        ]);
    }
}
