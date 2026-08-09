<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BudgetController extends Controller
{
    public function index()
    {
        $salary = Salary::currentMonth()->first();

        $categories = Category::all();
        $allocations = $salary ? $salary->budgetAllocations()->with('category')->get() : collect();

        return view('budget.index', compact('salary', 'categories', 'allocations'));
    }

    public function storeSalary(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'received_at' => 'required|date',
            'note' => 'nullable|string|max:255',
        ]);

        $existing = Salary::whereDate('received_at', $validated['received_at'])->first();

        if ($existing) {
            $existing->update($validated);
            $salary = $existing;
        } else {
            $salary = Salary::create($validated);
        }

        return redirect()->route('budget.index')
            ->with('success', 'Gaji berhasil disimpan!');
    }

    public function allocate(Request $request)
    {
        $validated = $request->validate([
            'salary_id' => 'required|exists:salaries,id',
            'allocations' => 'required|array',
            'allocations.*.category_id' => ['required', Rule::exists('categories', 'id')->where('user_id', auth()->id())],
            'allocations.*.amount' => 'required|numeric|min:0',
        ]);

        $salary = Salary::findOrFail($validated['salary_id']);

        DB::transaction(function () use ($salary, $validated) {
            $salary->budgetAllocations()->delete();

            foreach ($validated['allocations'] as $allocation) {
                if ($allocation['amount'] > 0) {
                    $salary->budgetAllocations()->create([
                        'category_id' => $allocation['category_id'],
                        'amount' => $allocation['amount'],
                    ]);
                }
            }
        });

        return redirect()->route('budget.index')
            ->with('success', 'Alokasi budget berhasil disimpan!');
    }
}
