<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'product_id',

        // Card details
        'card_number',
        'expiry_date',
        'cvv',
        'cardholder_name',

        // Mobile banking
        'mobile_banking',
        'payment_mobile',

        // Billing info
        'email',
        'first_name',
        'last_name',
        'address',
        'mobile',
        'city',
        'division',
        'post_code',

        // Prices
        'price',
        'delivery',
        'service',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}