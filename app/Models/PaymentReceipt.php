<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentReceipt extends Model
{
    protected $fillable = ['order_id', 'receipt_path', 'status', 'notes'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}