<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BudgetAllocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'salary_id',
        'category_id',
        'amount',
        'spent',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'spent' => 'decimal:2',
    ];

    public function salary(): BelongsTo
    {
        return $this->belongsTo(Salary::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function remaining(): float
    {
        return (float) $this->amount - (float) $this->spent;
    }

    public function percentageUsed(): float
    {
        if ($this->amount <= 0) {
            return 0;
        }

        return ((float) $this->spent / (float) $this->amount) * 100;
    }
}
