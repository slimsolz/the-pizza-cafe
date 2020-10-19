<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'user_id',
        'delivery_fee',
        'delivery_address',
        'sub_total',
        'currency',
        'zip_code',
        'first_name',
        'last_name',
        'email',
        'phone_number',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'sub_total' => 'float',
        'delivery_fee' => 'float',
        'zip_code' => 'integer'
    ];
}
