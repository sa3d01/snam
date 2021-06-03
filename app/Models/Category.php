<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modles\Ad;
use Illuminate\Support\Str;

class Category extends Model
{
    public function scopeActive($query)
    {
        $query->where('status','active');
    }
    public function activate()
    {
        $var = route('active_category',['id'=>$this->id]);
        $token = csrf_token();
        if($this->status=='active') {
            return "<form style='margin-top: 20px' method='POST' action='$var' class='form-horizontal'>
                <input type='hidden' name='_token' value='$token'>
                <button type='submit' class='btn btn-danger btn-rounded waves-effect waves-light'>
                <span class='btn-label'><i class='fa fa-times'></i></span>
                الغاء التفعيل</button>

            </form>";
        }
        return "<form style='margin-top: 20px' method='POST' action='$var' class='form'>
                <input type='hidden' name='_token' value='$token'>
                <button type='submit' class='btn btn-success btn-rounded waves-effect waves-light'>
                <span class='btn-label'><i class='fa fa-check'></i></span>
                تفعيل</button>

            </form>";
    }

    protected $fillable = ['status','name','parent_id','image'];
    protected $index_fields        = ['id','name','image'];
    public function setImageAttribute()
    {
        $file = request()->file('image');
        $destinationPath = 'images/category/';
        $filename = Str::random(10).'.'.$file->getClientOriginalExtension();
        $file->move($destinationPath, $filename);
        $this->attributes['image'] = $filename;
    }
    public function getImageAttribute()
    {
        try {
            if($this->attributes['image'] != null)
                return asset('images/category/').'/'.$this->attributes['image'];
            return asset('images/category/mSRpmJx6Xv.JPEG');
        } catch (\Exception $e) {
            return asset('images/category/mSRpmJx6Xv.JPEG');
        }
    }
    public function static_model()
    {
        $arr=[];
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
        }
        if ($this->parent_id==null){
            $arr['sub_categories']=$this->subs($this);
        }elseif($this->parent->parent_id==null){
            $arr['children']=$this->subs($this);
        } 
       // $child=$this->where('parent_id',$this->id)->first();
      //  $arr['have_child']= $child ? true : false ;

        return $arr;
    }
    public function subs($category)
    {
        $sub_categories=[];
        foreach($category->childs as $child){
            $sub_categories[]=$child->static_model();
        }
         return $sub_categories;
    }
    public function childs(){
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }
     public function parent(){
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }
    public function ads()
    {
        return $this->hasMany(Ad::class, 'ad_id', 'id');
    }

}
