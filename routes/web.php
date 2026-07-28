<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/budget', [BudgetController::class, 'index'])->name('budget.index');
Route::post('/budget/salary', [BudgetController::class, 'storeSalary'])->name('budget.salary.store');
Route::post('/budget/allocate', [BudgetController::class, 'allocate'])->name('budget.allocate');

Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
Route::patch('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

Route::get('/recurring', [RecurringExpenseController::class, 'index'])->name('recurring.index');
Route::get('/recurring/create', [RecurringExpenseController::class, 'create'])->name('recurring.create');
Route::post('/recurring', [RecurringExpenseController::class, 'store'])->name('recurring.store');
Route::patch('/recurring/{recurringExpense}', [RecurringExpenseController::class, 'update'])->name('recurring.update');
Route::delete('/recurring/{recurringExpense}', [RecurringExpenseController::class, 'destroy'])->name('recurring.destroy');
Route::post('/recurring/{recurringExpense}/pay', [RecurringExpenseController::class, 'markPaid'])->name('recurring.pay');

Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
