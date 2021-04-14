<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use App\User;
use Auth;
use Illuminate\Support\Str;

class MasterController extends Controller
{
    /**
    *basic requirements
        all active data like users or categories with its relations
        specific shop or user with relations
        search
        delete item
        add item
        edit item
        register,login,update_profile and forget password
     */

    protected $model;
    protected $related_model;
    protected $related_model_name;
    protected $route;
    protected $perPage = 10;
    protected $index_fields;
    protected $apiToken;
    protected $create_fields;
    protected $json_fields;

    public function __construct()
    {
        $this->middleware(function($request, $next){
            $this->auth = Auth::guard('api')->user();
            return $next($request);
        });
    }
    public function update_tokens($user,$device_token)
    {
        $device_tokens=[];
        //  $tokens= json_decode($user->device_token);
        if($user->device_token!=null){
            if(gettype($user->device_token)=='string'){
                $user_device_token=(array)$user->device_token;
            }else{
                $user_device_token=$user->device_token;
            }
            foreach ($user_device_token as $token){
                $device_tokens[]=$token;
            }
        }
        if(!in_array($device_token,$device_tokens)){
            $device_tokens[]=$device_token;
        }
        $user->update(['device_token'=>$device_tokens,'apiToken'=>Str::random(60)]);
    }
    public function update_apiToken($user)
    {
        $user->update(['apiToken'=>Str::random(60)]);
    }
    public function check_apiToken($apiToken)
    {
        if($apiToken){
            try{
                $split = explode("sa3d01",$apiToken);
                $user=User::where('apiToken',$split['1'])->first();
            }catch (\Exception $e){
                return response()->json(['status' => 401 ,'msg' => 'Invalid authentication token in request']);
            }
            if(!$user){
                return response()->json(['status' => 401 ,'msg' => 'Invalid authentication token in request ']);
            }
        }else{
            return response()->json(['status' => 401 ,'msg' => 'Invalid authentication token in request ']);
        }
    }
    public function index(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token && $this->apiToken == true){
            return $check_token;
        }
        //auth
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        $page = $request->header('page', 1);
        $count = $this->model->count();
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;
        $rows = $this->model->latest()->offset($offset)->limit(10)->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            if ($this->related_model){
                if($this->route=='shops'){
                    $arx=[];
                    $orders=Order::where(['shop_id'=>$row->id,'client_id'=>$user->id,'status'=>'waiting'])->get();
                    foreach ($orders as $order){
                        $arx[]=$order->static_model();
                    }
                    $arr['orders'] =$arx;
                }else{
                    $related_model_name=$this->related_model_name;
                    $arx=[];
                    foreach ($row->$related_model_name as $related_row){
                        $arx[]=$related_row->static_model();
                    }
                    $arr[$this->related_model_name] =$arx;
                }
            }
            $data[]=$arr;
        }
        if($count>0){
            $pages_count=(integer)round($count/10);
            if($pages_count==0){
                $pages_count=1;
            }
        }
        else{
            $pages_count=0;
        }
        return response()->json(['value' => true,'data_count'=>$count,'pages_count'=>$pages_count,'data'=>$data,'apiToken'=>$user->apiToken]);
    }
    public function store(Request $request)
    {
        //auth
        if($this->route != 'users'){
            $check_token=$this->check_apiToken($request->header('apiToken'));
            if($check_token && $this->apiToken == true){
                return $check_token;
            }elseif(!$check_token && $this->apiToken == true ){
                $split = explode("sa3d01",$request->header('apiToken'));
                $user=User::where('apiToken',$split['1'])->first();
                $this->update_apiToken(User::find($user->id));
            }
        }
        //auth
        $lang=$request->header('lang','ar');
        $validator = Validator::make($request->all(),$this->validation_rules(1),$this->validation_messages($lang));
        if ($validator->passes()) {
            if($this->route != 'users'){
                 $obj=$this->model->create($request->all());
            }
            if($this->route == 'users'){
                $obj=$this->model->create(['mobile'=>$request['mobile'],'username'=>$request['username'],'city'=>$request['city'],'password'=>$request['password'],'country_key'=>$request['country_key']]);
                $activation_code=Str::random(5);
                //send_sms
                $this->update_tokens(User::find($obj->id),$request->serial_num,$request->device_token);
                $obj->refresh();
                return response()->json(['value' => true,'activation_code'=>$activation_code,'data' => $obj->static_model(),'apiToken' => $obj->apiToken]);
            }elseif ($this->route=='orders'){
                $user->update(['lat'=>$request['client_lat'],'long'=>$request['client_long']]);
                $request['shop_id'] ? $id=$request['shop_id'] : $id=$user->id ;
                $obj->notify_drivers($user,$id);
            }
            $obj->refresh();
            return response()->json(['value' => true,'data' => $obj->static_model(),'apiToken' => $user->apiToken]);
        }
        else {
            return response()->json(['value' => false, 'msg' =>$validator->errors()->first()]);
        }
    }
    public function update($id,Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token && $this->apiToken == true){
            return $check_token;
        }
        //auth
        $lang=$request->header('lang','ar');
        $validator = Validator::make($request->all(),$this->validation_rules(2,$id),$this->validation_messages($lang));
        if ($validator->passes()) {
            $row = $this->model->find($id);
            if (!$row) {
                return response()->json(['value' => false]);
            }
            if($this->route == 'users'){
                $row->update(['username'=>$request['username'],'mobile'=>$request['mobile'],'city'=>$request['city'],'email'=>$request['email']]);
                $this->update_tokens($row,$request->serial_num,$request->device_token);
            }else{
                $row->update($request->all());
            }
            $row->refresh();
            return response()->json(['value' => true,'data' => $row->static_model(),'apiToken' => $row->apiToken]);
        }
        else {
            return response()->json(['value' => false, 'msg' =>$validator->errors()->first()]);
        }
    }
    public function show($id, Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token && $this->apiToken == true){
            return $check_token;
        }
        //auth
        $row = $this->model->find($id);
        if (!$row) {
            return response()->json(['value' => false]);
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        return response()->json(['value' => true, 'data' => $row->static_model(),'apiToken'=>$user->apiToken]);
    }
    public function destroy($id,Request $request) {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token && $this->apiToken == true){
            return $check_token;
        }
        //auth
        $this->model->find($id)->delete();
        return response()->json(['value' => true]);
    }
}