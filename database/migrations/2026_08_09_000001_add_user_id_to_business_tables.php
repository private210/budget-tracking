<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = ['categories', 'salaries', 'budget_allocations', 'expenses', 'recurring_expenses'];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            if (! Schema::hasColumn($table, 'user_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
                });
            }

            if (! Schema::hasIndex($table, $table.'_user_id_index')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->index('user_id');
                });
            }
        }

        $firstUserId = DB::table('users')->orderBy('id')->value('id');

        if ($firstUserId) {
            foreach (self::TABLES as $table) {
                DB::table($table)->whereNull('user_id')->update(['user_id' => $firstUserId]);
            }
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            if (Schema::hasColumn($table, 'user_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropConstrainedForeignId('user_id');
                });
            }
        }
    }
};
