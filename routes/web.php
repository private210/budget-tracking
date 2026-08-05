<?php

use App\Http\Controllers\BudgetController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RecurringExpenseController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
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

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::patch('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

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
        $data['db_diag'] = DB::select('select version()')[0]->version ?? 'n/a';
        $data['db_tables'] = array_map(fn ($t) => $t->tablename, DB::select("select tablename from pg_tables where schemaname = 'public' order by tablename"));
        $pdo->exec('DROP TABLE IF EXISTS _diag_users');
        $pdo->exec('CREATE TABLE _diag_users (id bigint NOT NULL, name varchar(255) NOT NULL, email varchar(255) NOT NULL, email_verified_at timestamp(0) WITHOUT TIME ZONE NULL, password varchar(255) NOT NULL, remember_token varchar(100) NULL, created_at timestamp(0) WITHOUT TIME ZONE NULL, updated_at timestamp(0) WITHOUT TIME ZONE NULL)');
        $pdo->exec('ALTER TABLE _diag_users ADD PRIMARY KEY (id)');
        $pdo->exec('ALTER TABLE _diag_users ADD CONSTRAINT _diag_users_email_unique UNIQUE (email)');
        $pdo->exec('DROP TABLE _diag_users');
        $data['db_diag_step'] = 'OK';
        $pdo->exec('DROP TABLE IF EXISTS _diag_tx');
        try {
            $pdo->beginTransaction();
            $pdo->exec('CREATE TABLE _diag_tx (id bigint NOT NULL, email varchar(255) NOT NULL)');
            $pdo->exec('ALTER TABLE _diag_tx ADD PRIMARY KEY (id)');
            $pdo->exec('ALTER TABLE _diag_tx ADD CONSTRAINT _diag_tx_email_unique UNIQUE (email)');
            $pdo->commit();
            $data['db_diag_tx'] = 'OK';
        } catch (Throwable $e) {
            $pdo->rollBack();
            $data['db_diag_tx'] = 'FAIL: '.$e->getMessage();
        }
        $pdo->exec('DROP TABLE IF EXISTS _diag_tx');
    } catch (Throwable $e) {
        $data['db_test'] = 'FAIL: '.$e->getMessage();
    }

    return response()->json($data, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
})->withoutMiddleware('web');

// One-time migration runner for shared hosting (InfinityFree)
Route::get('/migrate', function () {
    try {
        Artisan::call('migrate:fresh', ['--force' => true]);
        Artisan::call('db:seed', ['--force' => true, '--class' => 'Database\\Seeders\\CategorySeeder']);
    } catch (Throwable $e) {
        return response('MIGRATE ERROR: '.$e->getMessage(), 500);
    }

    return 'Migrations & seeders completed! Delete this route from routes/web.php.';
});
