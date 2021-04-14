<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = ['image'];
    protected $index_fields        = ['id','image'];
    public function static_model()
    {
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
        }
        return $arr;
    }
    public function setImageAttribute()
    {
        $file = request()->file('image');
        $destinationPath = 'images/slider/';
        $filename = $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $this->attributes['image'] = $filename;
    }
    public function getImageAttribute()
    {
        try {
            if($this->attributes['image'] != null)
                return asset('images/slider/').'/'.$this->attributes['image'];
            return asset('images/user/admin.png');
        } catch (\Exception $e) {
            return asset('images/user/admin.png');
        }
    }
}
