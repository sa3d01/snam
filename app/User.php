<?php

namespace App;

use App\Models\Ad;
use App\Models\City;
use App\Models\Notification;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function scopeActive($query)
    {
        $query->where('status','active');
    }
    protected $casts = [
        'device_token' => 'json',
    ];
    protected $fillable = [
        'username','device_token','apiToken', 'mobile', 'password', 'status', 'approved', 'online','city_id','image'
    ];
    protected $index_fields  =  ['id','username','apiToken', 'mobile', 'status', 'approved', 'online','city_id','image'];

    public function static_model()
    {
        $arr=[];
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
        $count_rating=Rating::where('rated_id',$this->id)->count();
        $sum_rating=Rating::where('rated_id',$this->id)->sum('rate');
        $arr['total_rating'] = $count_rating==0 ? 0 : round($sum_rating/$count_rating);
        $arr['district']=$this->city? $this->city->district->static_model() : null;
        $arr['from']=Carbon::parse($this->created_at)->diffForHumans();
        return $arr;
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class,'rated_id','id');
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class,'receiver_id','id');
    }
    public function ads()
    {
        return $this->hasMany(Ad::class,'user_id','id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id', 'id');
    }
    public function getDistanceBetweenPoints($shop_lat, $shop_long, $unit = 'Km')
    {
        $user_lat=$this->attributes['lat'];
        $user_long=$this->attributes['long'];
        $theta = $user_long - $shop_long;
        $distance = (sin(deg2rad($user_lat)) * sin(deg2rad($shop_lat))) + (cos(deg2rad($user_lat)) * cos(deg2rad($shop_lat)) * cos(deg2rad($theta)));
        $distance = acos($distance);
        $distance = rad2deg($distance);
        $distance = $distance * 60 * 1.1515;
        switch($unit) {
            case 'Mi': break; case 'Km' : $distance = $distance * 1.609344;
        }
        return (round($distance));
    }
    public function setImageAttribute()
    {
        if (is_file(request()->image)){
            $file = request()->file('image');
            $destinationPath = 'images/user/';
            $filename = $file->getClientOriginalName();
            $file->move($destinationPath, $filename);
            $this->attributes['image'] = $filename;
        }else{
            $this->attributes['image'] = request()->image;
        }

    }
    public function getImageAttribute()
    {
        try {
            if($this->attributes['image'] != null)
                return asset('images/user/').'/'.$this->attributes['image'];
            return asset('images/user/default.jpeg');
        } catch (\Exception $e) {
            return asset('images/user/default.jpeg');
        }
    }
    public function setPasswordAttribute($password)
    {
        if (isset($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }
    public function activate()
    {
        $var = route('active_user',['id'=>$this->id]);
        $token = csrf_token();
        if($this->approved=='true') {
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

    public function setMobileAttribute($mobile){
        $first_val = substr($mobile, 0, 1 );
        if ($first_val=="0") {
            $this->attributes['mobile'] = '+966'.substr($mobile, 1);
        }elseif($first_val == "5"){
            $this->attributes['mobile'] = '+966'.$mobile;
        }else{
            $this->attributes['mobile']=$mobile;
        }
    }
    public static function sendMessage($message, $phoneNumber)
    {
        $getdata = http_build_query(
            $fields = array(
                "Username" => "0555001022",
                "Password" => "123456",
                "Message" => $message,
                "RecepientNumber" => $phoneNumber,
                "ReplacementList" => "",
                "SendDateTime" => "0",
                "EnableDR" => False,
                "Tagname" => "snam",
                "VariableList" => "0"
            ));
        $opts = array('http' =>
            array(
                'method' => 'GET',
                'header' => 'Content-type: application/x-www-form-urlencoded',

            )
        );
        $context = stream_context_create($opts);
        $results = file_get_contents('http://api.yamamah.com/SendSMSV2?' . $getdata, false, $context);
        return $results;
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
