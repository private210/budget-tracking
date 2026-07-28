<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('amount', 15, 2);
            $table->enum('frequency', ['weekly', 'monthly', 'yearly'])->default('monthly');
            $table->date('next_due_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['next_due_date', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recurring_expenses');
    }
};
