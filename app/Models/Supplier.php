<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
} 