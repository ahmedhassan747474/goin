<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table="notifications";

    protected $fillable = [
        'title_en', 'title_ar', 'content_en', 'content_ar', 'image', 
        'user_id', 'restaurant_id', 'product_id', 'type', 'type_id', 
        'is_seen', 'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id','id');
    }
}