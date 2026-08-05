<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;

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

// Temporary Vercel diagnostic — delete after deploy works
Route::get('/debug', function () {
    $url = env('DB_URL');
    $data = [
        'app_key' => env('APP_KEY') ? 'set' : 'MISSING',
        'app_url' => env('APP_URL') ?: 'MISSING',
        'db_connection' => config('database.default'),
        'db_url' => $url ? 'set' : 'MISSING',
        'db_url_host' => $url ? (parse_url($url, PHP_URL_HOST) ?: 'PARSE FAIL') : null,
        'db_sslmode' => config('database.connections.pgsql.sslmode'),
        'php' => PHP_VERSION,
        'ext_pdo_pgsql' => extension_loaded('pdo_pgsql') ? 'yes' : 'NO',
    ];
    try {
        $pdo = DB::connection()->getPdo();
        $data['db_test'] = 'OK ('.$pdo->getAttribute(PDO::ATTR_SERVER_VERSION).')';
    } catch (Throwable $e) {
        $data['db_test'] = 'FAIL: '.$e->getMessage();
    }

    return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->withoutMiddleware('web');

// One-time migration runner for shared hosting (InfinityFree)
Route::get('/migrate', function () {
    try {
        Artisan::call('migrate', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true, '--class' => 'Database\\Seeders\\CategorySeeder']);
    } catch (\Throwable $e) {
        return response('MIGRATE ERROR: ' . $e->getMessage(), 500);
    }

    return 'Migrations & seeders completed! Delete this route from routes/web.php.';
});
