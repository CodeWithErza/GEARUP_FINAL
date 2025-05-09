<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    protected $fillable = ['reference_number', 'date', 'type', 'notes', 'processed_by'];

    public function items()
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
