<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id', 
        'guest_name', 
        'guest_email', 
        'guest_phone', 
        'guest_address',
        'subtotal', 
        'total', 
        'status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function receipt(): HasMany
    {
        return $this->hasMany(PaymentReceipt::class);
    }

    /**
     * Check if this is a guest order
     */
    public function isGuestOrder(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Get customer name (user or guest)
     */
    public function getCustomerNameAttribute(): string
    {
        return $this->isGuestOrder() ? $this->guest_name : $this->user->name;
    }

    /**
     * Get customer email (user or guest)
     */
    public function getCustomerEmailAttribute(): string
    {
        return $this->isGuestOrder() ? $this->guest_email : $this->user->email;
    }
}
