<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use App\Models\Rating;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Http\Request;
use Edujugon\PushNotification\PushNotification;


class NotificationController extends MasterController
{
    public function __construct(Notification $model)
    {
        $this->model = $model;
        parent::__construct();
    }
    public function index(Request $request)
    {
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        //auth
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        if(!$user){
            return response()->json(['status' => 401]);
        }
        $rows = $this->model->where('receiver_id',$user->id)->latest()->get();
        $unread_count = $this->model->where(['receiver_id'=>$user->id,'read'=>0])->count();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data,'unread_count'=>$unread_count]);
    }
    public function show($id, Request $request)
    {
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        //auth
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        $row = $this->model->find($id);
        if (!$row) {
            return response()->json(['status' => 400, 'msg' => 'something happen']);
        }
        $row->update(['read'=>1]);
        $unread_count = $this->model->where(['receiver_id'=>$user->id,'read'=>0])->count();
        return response()->json(['status' => 200,'unread_count'=>$unread_count, 'data' => $row->static_model()]);
    }
}
