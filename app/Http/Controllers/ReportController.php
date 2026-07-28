<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Salary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        $startDate = now()->startOfMonth()->setDate(
            explode('-', $month)[0],
            explode('-', $month)[1],
            1
        );
        $endDate = $startDate->copy()->endOfMonth();

        $salary = Salary::where('received_at', '>=', $startDate)
            ->where('received_at', '<=', $endDate)
            ->first();

        $categoryBreakdown = Expense::select('category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $totalExpenses = $categoryBreakdown->sum('total');

        $dailyExpenses = Expense::select(DB::raw('DATE(spent_at) as date'), DB::raw('SUM(amount) as total'))
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topExpenses = Expense::with('category')
            ->whereBetween('spent_at', [$startDate, $endDate])
            ->orderByDesc('amount')
            ->limit(10)
            ->get();

        $months = Salary::selectRaw("strftime('%Y-%m', received_at) as month")
            ->orderByDesc('month')
            ->limit(12)
            ->pluck('month');

        return view('reports.index', compact(
            'salary',
            'categoryBreakdown',
            'totalExpenses',
            'dailyExpenses',
            'topExpenses',
            'month',
            'months'
        ));
    }
}
