<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->date('received_at');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['received_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};
