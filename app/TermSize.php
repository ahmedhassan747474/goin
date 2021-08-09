<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TermSize extends Model
{
    protected $table="term_size";

    public function size()
    {
        return $this->belongsTo('App\Size','size_id','id');
    }

    public function product()
    {
        return $this->belongsTo('App\Terms','product_id','id');
    }
}