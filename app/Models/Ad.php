<?php

namespace App\Models;

use App\User;
use App\Models\Chat;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $casts = [
        'images' => 'array',
        'videos' => 'array',
    ];
    protected $fillable = ['title','note','images','status','vip','mobile','message','live_time','user_id','city_id','category_id','price','videos','lat','lng'];
    protected $index_fields        = ['id','title','note','images','videos','lat','lng','status','user_id','city_id','category_id'];
    public function static_model()
    {
        foreach ($this->index_fields as $index_field){
            if(substr($index_field, "-3")=='_id'){
                $related_model=substr_replace($index_field, "", -3);
                if($this->$related_model !=null){
                    $model=$this->$related_model->static_model();
                }else{
                    $model=null;
                }
                $this->$index_field ? $arr[$related_model] = $model : $arr[$related_model] =null;
            }elseif (substr($index_field, "-3")!='_id'){
                $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] =null;
            }
        }
        // $images=[];
        // foreach ($this->images as $image){
        //     $images[]=asset('images/ads/').'/'.$image;
        // }
        // $arr['images']=$images;

        // $videos=[];
        // if($this->videos!=null){
        //   foreach ($this->videos as $video){
        //         $videos[]=asset('images/ads/').'/'.$video;
        //     }
        // }

        // $arr['videos']=$videos;

        $arr['url']=\URL::to('ad/'.$this->id);
        $arr['district']=$this->city? $this->city->district->static_model() : null;

        $arr['published']=$this->published();
        $comments=Comment::where('ad_id',$this->id)->get();
        $comments_array=[];
        foreach ($comments as $comment){
            $comments_array[]=$comment->static_model();
        }
        $arr['comments'] = $comments_array;
        $arr['parent_category'] = $this->category->parent ? $this->category->parent->static_model() : $this->category->static_model();
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
        $date=explode(',',$this->time($created_at->timestamp));
        return $date[0];
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
                    $time .= ' , ';
            }
        }
        $st = 'منذ ' . $time;
        return $st;
    }
}
