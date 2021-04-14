<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Article extends Model
{

    public function activate()
    {
        $var = route('active_article',['id'=>$this->id]);
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
    public function setImageAttribute()
    {
        $file = request()->file('image');
        $destinationPath = 'images/illustrations/';
        $filename = $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $this->attributes['image'] = $filename;
    }
    public function getImageAttribute()
    {
        if($this->attributes['image']!=null){
            return 'https://souq-tubok.com/public/images/illustrations/'.'/'.$this->attributes['image'];
        }
        return asset('images/illustrations/default.png');
    }
    protected $fillable = [
        'title','note','image'
    ];
    protected $index_fields        = ['id','title','note','image'];
 //   protected $json_fields        = ['company_name','name'];
   // protected $casts = ['name' => 'json','company_name' => 'json'];
    public function static_model()
    {
        $lang=request()->header('lang','ar');
        $arr=[];
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
        }
        $arr['published']=$this->published();
        return $arr;
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
