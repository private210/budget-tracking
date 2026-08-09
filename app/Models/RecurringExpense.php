<?php

namespace App\Models;

use App\Models\Concerns\ScopedByUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecurringExpense extends Model
{
    use HasFactory, ScopedByUser;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'amount',
        'frequency',
        'next_due_date',
        'is_active',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'next_due_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function isDue(): bool
    {
        return $this->is_active && $this->next_due_date->lte(now());
    }

    public function markAsPaid(): void
    {
        match ($this->frequency) {
            'weekly' => $this->update(['next_due_date' => $this->next_due_date->addWeek()]),
            'monthly' => $this->update(['next_due_date' => $this->next_due_date->addMonth()]),
            'yearly' => $this->update(['next_due_date' => $this->next_due_date->addYear()]),
        };
    }
}
