<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'productid', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'adminid', 'id');
    }
}
