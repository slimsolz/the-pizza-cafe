<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image_url', 'public_id', 'pizza_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function pizza() {
        return $this->belongsTo(Pizza::class);
    }
}
