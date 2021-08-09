<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableImage extends Model
{
    protected $table="table_image";

    protected $fillable = [
        'image', 'table_id', 'created_at', 'updated_at'
    ];

    public function table()
    {
        return $this->belongsTo('App\Table','table_id','id');
    }
}