<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $casts = [
        'images' => 'array',
    ];
    protected $fillable = ['title','note','images','status','vip','mobile','message','live_time','user_id','city_id','category_id'];
    protected $index_fields        = ['id','title','note','images','status','vip','mobile','message','live_time','user_id','city_id','category_id'];
    public function static_model()
    {
        foreach ($this->index_fields as $index_field){
            if(substr($index_field, "-3")=='_id'){
                $related_model=substr_replace($index_field, "", -3);
                if($this->$related_model !=null){
                    $model=$this->$related_model->static_model();
                }else{
                    $model='';
                }
                $this->$index_field ? $arr[$related_model] = $model : $arr[$related_model] ='';
            }elseif (substr($index_field, "-3")!='_id'){
                $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
            }
        }
        $images=[];
        foreach ($this->images as $image){
            $images[]=asset('images/ads/').'/'.$image;
        }
        $arr['images']=$images;
        $arr['published']=$this->published();
        $comments=Comment::where('ad_id',$this->id)->get();
        $comments_array=[];
        foreach ($comments as $comment){
            $comments_array[]=$comment->static_model();
        }
        $arr['comments'] = $comments_array;
        return $arr;
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
    public function published()
    {
        $created_at=Carbon::parse($this->attributes['created_at']);
        return $this->time($created_at->timestamp);
    }
    public static function time($timestamp, $num_times = 3)
    {

        $times = array(31536000 => 'سنة', 2592000 => 'شهر', 604800 => 'اسبوع', 86400 => 'يوم', 3600 => 'ساعة', 60 => 'دقيقة', 1 => 'ثانية');

        $now = time() - 3600;
        $timestamp -= 3600;
        $secs = $now - $timestamp;
        if ($secs == 0) {
            $secs = 1;
        }

        $count = 0;
        $time = '';

        foreach ($times as $key => $value) {
            if ($secs >= $key) {
                $s = '';
                $time .= floor($secs / $key);

                if ((floor($secs / $key) != 1)) $s = ' ';

                $time .= ' ' . $value . $s;
                $count++;
                $secs = $secs % $key;

                if ($count > $num_times - 1 || $secs == 0) break; else
                    $time .= ' ، ';
            }
        }
        $st = 'منذ ' . $time;
        return $st;
    }
}
