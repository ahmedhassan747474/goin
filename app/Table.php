<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $table="tables";

    protected $fillable = [
        'name_en', 'name_ar', 'no_guest', 'status', 'restaurant_id', 'created_at', 'updated_at'
    ];

    public function restaurant()
    {
        return $this->belongsTo('App\User','restaurant_id','id');
    }
    
    public function images()
    {
        return $this->hasMany('App\TableImage');
    }
    
    // public function days()
    // {
    //     return $this->hasMany('App\TableDay');
    // }
}