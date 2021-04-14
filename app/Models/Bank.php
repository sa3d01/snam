<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{

    public function activate()
    {
        $var = route('active_bank',['id'=>$this->id]);
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
    public function setLogoAttribute()
    {
        $file = request()->file('logo');
        $destinationPath = 'images/bank/';
        $filename = $file->getClientOriginalName();
        $file->move($destinationPath, $filename);
        $this->attributes['logo'] = $filename;
    }
    public function getLogoAttribute()
    {
        if($this->attributes['logo']!=null){
            return asset('images/bank/').'/'.$this->attributes['logo'];
        }
        return asset('images/bank/default.png');
    }
    protected $fillable = [
        'account_number','company_name','name','logo','iban'
    ];
    protected $index_fields        = ['id','company_name','name','account_number','logo','iban'];
 //   protected $json_fields        = ['company_name','name'];
   // protected $casts = ['name' => 'json','company_name' => 'json'];
    public function static_model()
    {
        $lang=request()->header('lang','ar');
        $arr=[];
        foreach ($this->index_fields as $index_field){
            $this->$index_field ? $arr[$index_field] = $this->$index_field : $arr[$index_field] ='';
        }
        return $arr;
    }
}
