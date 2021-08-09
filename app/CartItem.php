<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $table="cart_item";

    protected $fillable = [
        'cart_id', 'product_id', 'size_id', 'quantity', 'price', 'special_request', 'created_at', 'updated_at'
    ];

    public function size()
    {
        return $this->belongsTo('App\TermSize','size_id','id');
    }

    public function product()
    {
        return $this->belongsTo('App\Terms','product_id','id');
    }

    public function cart()
    {
        return $this->belongsTo('App\Cart', 'cart_id', 'id');
    }

    public function addons()
    {
        return $this->hasMany('App\CartItemAddons','cart_item_id','id');
    }
}