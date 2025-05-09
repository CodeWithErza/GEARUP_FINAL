<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stockin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'invoice_number',
        'date',
        'total_amount',
        'status',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockinItem::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'stockin_items')
            ->withPivot(['quantity', 'unit_price', 'total_price'])
            ->withTimestamps();
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
} 