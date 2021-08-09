<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $table="sliders";

    public function user()
    {
        return $this->belongsTo('App\User','restaurant_id','id');
    }

    public function product()
    {
        return $this->belongsTo('App\Terms','product_id','id');
    }
}
