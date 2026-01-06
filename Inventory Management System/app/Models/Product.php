<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    protected $fillable = ['name', 'brand', 'category', 'quantity', 'price', 'status', 'userid'];
}
