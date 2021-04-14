<?php

namespace App\Models;

use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable=['title','note','sender_id','receiver_id','ad_id','type','read','reply_type','msg_id','collective_notice'];
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id', 'id');
    }
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id', 'id');
    }
    public function ad()
    {
        return $this->belongsTo(Ad::class, 'ad_id', 'id');
    }
    protected $index_fields  =  ['id','type','title','note','created_at'];
    public function static_model()
    {
        $arr=[];
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : null;
        }
        $arr['read']=$this->read === 1 ? true : false;
        return $arr;
    }
    public function getCreatedAtAttribute()
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
