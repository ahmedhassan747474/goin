<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $table="reservations";

    public function vendorinfo()
    {
    	return $this->hasOne('App\User','id','vendor_id')->with('location','info','avg_ratting');
    }

    public function reservationlog()
    {
        return $this->hasMany('App\Reservationlog');
    }

    public function restaurantinfo()
    {
        return $this->hasOne('App\User','id','vendor_id')->select('id','name', 'email', 'phone');
    }

    public function customerinfo()
    {
        return $this->hasOne('App\User','id','user_id')->select('id','name', 'email', 'phone');
    }

    public function review()
    {
        return $this->belongsTo('App\Comment','id','order_id');
    }

    public function vendor()
    {
        return $this->hasOne('App\User','id','vendor_id');
    }
}
