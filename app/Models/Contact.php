<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = ['name','email','message'];
    protected $index_fields        = ['id','name','email','message'];
    protected $json_fields        = ['name','email','message'];
    public function static_model()
    {
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
        }
        return $arr;
    }
}
