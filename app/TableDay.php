<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TableDay extends Model
{
    protected $table="table_day";

    protected $fillable = [
        'table_id', 'status', 'day', 'open', 'close', 'created_at', 'updated_at'
    ];

    public function table()
    {
        return $this->belongsTo('App\Table','table_id','id');
    }
}