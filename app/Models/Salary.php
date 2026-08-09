<?php

namespace App\Models;

use App\Models\Concerns\ScopedByUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salary extends Model
{
    use HasFactory, ScopedByUser;

    protected $fillable = [
        'user_id',
        'amount',
        'received_at',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'received_at' => 'date',
    ];

    public function budgetAllocations(): HasMany
    {
        return $this->hasMany(BudgetAllocation::class);
    }

    public function totalAllocated(): float
    {
        return (float) $this->budgetAllocations()->sum('amount');
    }

    public function totalSpent(): float
    {
        return (float) $this->budgetAllocations()->sum('spent');
    }

    public function remaining(): float
    {
        return (float) $this->amount - $this->totalAllocated();
    }
}
