<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'budget_allocation_id',
        'amount',
        'description',
        'spent_at',
        'is_recurring',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent_at' => 'date',
        'is_recurring' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function budgetAllocation(): BelongsTo
    {
        return $this->belongsTo(BudgetAllocation::class);
    }
}
