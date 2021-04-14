<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{

//    protected $casts = [
//        'name' => 'json',
//    ];
    protected $fillable = [
        'name','status','district_id','type'
    ];
    protected $index_fields        = ['id','name'];
    public function static_model()
    {
        $arr=[];
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
        }
        return $arr;
    }
    public function district()
    {
        return $this->belongsTo(City::class, 'district_id', 'id');
    }
    public function cities()
    {
        return $this->hasMany(City::class,'district_id','id');
    }
}
