<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ordermeta extends Model
{
    protected $table="order_meta";

    public function products()
    {
    	return $this->hasOne('App\Terms','id','term_id')->with('preview','postcategory', 'addons')->select('id','title_en', 'title_ar','type');
    }
    
    public function product()
    {
    	return $this->hasOne('App\Terms','id','term_id')->select('id','title_en', 'title_ar','type');
    }
    
    public function size()
    {
    	return $this->hasOne('App\TermSize','id','size_id');
    }
    
    public function addons()
    {
        return $this->hasMany('App\OrderMetaAddon', 'order_meta_id');
    }
    
}
