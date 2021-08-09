<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CartItemAddons extends Model
{
    protected $table="cart_item_addons";

    protected $fillable = [
        'cart_item_id', 'addons_id', 'created_at', 'updated_at'
    ];

    public function addon()
    {
        return $this->belongsTo('App\Terms','addons_id','id')->with('price');
    }
}