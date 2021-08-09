<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderMetaAddon extends Model
{
    protected $table="order_meta_addon";

    public function addon()
    {
    	return $this->belongsTo('App\Terms','addons_id','id')->with('price');
    }
    
}
