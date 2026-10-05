<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'customer_name',
        'email',
        'phone',
        'country',
        'city',
        'neighborhood',
        'address',
        'landmark',
        'delivery_method',
        'payment_method',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'total',
        'status',
        'customer_note',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Client
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Produits de la commande
    |--------------------------------------------------------------------------
    */

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}