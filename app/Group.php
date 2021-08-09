<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $table="groups";

    public  function products()
    {
      return $this->belongsToMany('App\Terms','group_product','group_id','product_id')->with('price','preview','addons')->where('status',1)->where('terms.type',6);
    }
}
