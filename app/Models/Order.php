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
        'zip_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at'
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

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}
