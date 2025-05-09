<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustmentItem extends Model
{
    protected $fillable = ['stock_adjustment_id', 'product_id', 'current_stock', 'new_count', 'difference', 'reason'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}