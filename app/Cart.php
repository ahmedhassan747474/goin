<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table="carts";

    protected $fillable = [
        'user_id', 'restaurant_id', 'created_at', 'updated_at'
    ];

    public function restaurant()
    {
        return $this->belongsTo('App\User','restaurant_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }

    public function items()
    {
        return $this->hasMany('App\CartItem','cart_id','id');
    }
}