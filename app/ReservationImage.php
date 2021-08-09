<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationImage extends Model
{
    protected $table="reservation_image";

    public function user()
    {
        return $this->belongsTo('App\User','restaurant_id','id');
    }

}
