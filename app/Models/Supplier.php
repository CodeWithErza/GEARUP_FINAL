<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_code',
        'name',
        'contact_person',
        'position',
        'phone',
        'email',
        'address',
        'payment_terms',
        'status',
        'notes'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Generate a unique supplier code before saving
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($supplier) {
            if (!$supplier->supplier_code) {
                $supplier->supplier_code = 'SUP-' . str_pad(static::max('id') + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    /**
     * Get the stockins for the supplier.
     */
    public function stockins(): HasMany
    {
        return $this->hasMany(Stockin::class);
    }

    /**
     * Get the products supplied by this supplier.
     */
    public function products()
    {
        // This uses a custom query to get products through stockins
        return Product::whereIn('id', function($query) {
            $query->select('product_id')
                ->from('stockin_items')
                ->join('stockins', 'stockin_items.stockin_id', '=', 'stockins.id')
                ->where('stockins.supplier_id', $this->id);
        });
    }

    /**
     * Get the count of unique products supplied by this supplier.
     */
    public function getProductCountAttribute()
    {
        return $this->stockins()
            ->join('stockin_items', 'stockins.id', '=', 'stockin_items.stockin_id')
            ->select('stockin_items.product_id')
            ->distinct()
            ->count();
    }
} 