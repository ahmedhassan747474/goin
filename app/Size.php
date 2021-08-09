<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $table="sizes";

    public function user()
    {
        return $this->belongsTo('App\User','restaurant_id','id');
    }
}