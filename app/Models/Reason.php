<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $fillable = ['name','type'];
    protected $index_fields  = ['id','name'];

    public function static_model()
    {
        $arr=[];
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
        }
        return $arr;
    }
    public function getTypeAttribute()
    {
        $status=$this->attributes['type'];
        if($status=='contact'){
            $status='تواصل';
        }elseif($status=='reject'){
            $status='تبليغ';
        }
        return $status;
    }
}
