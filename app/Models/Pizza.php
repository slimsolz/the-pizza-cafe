<?php

namespace App\Models;

use App\Models\Cart;
use App\Models\Photo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pizza extends Model
{
    use HasFactory;

     /**
      * The attributes that are mass assignable.
      *
      * @var array
      */
     protected $fillable = [
        'name',
        'description',
        'size',
        'price',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     *
    */
    protected $hidden = ['created_at', 'updated_at'];

    protected $casts = [
        'price' => 'float'
    ];

    public function photo() {
        return $this->hasOne(Photo::class, 'pizza_id', 'id');
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
