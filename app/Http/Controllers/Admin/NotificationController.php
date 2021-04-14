<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\User;
use Edujugon\PushNotification\PushNotification;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends MasterController
{
    public function __construct(Notification $model)
    {
        $this->model = $model;
        $this->route = 'notification';
        $this->module_name         = 'قائمة الاشعارات ';
        $this->single_module_name  = 'اشعار';
        parent::__construct();
    }

    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['note'] ='required';
        return $therulesarray;
    }
    public function create()
    {
        return view('admin.' . $this->route . '.create');
    }
    public function index()
    {
        $rows = $this->model->where(['type'=>'admin','collective_notice'=>'true'])->latest()->get();
        return view('admin.' . $this->route . '.index', compact('rows'));
    }
    public function collective_notice()
    {
        $rows = $this->model->where(['type'=>'admin','collective_notice'=>'true'])->latest()->get();
        $collection = collect($rows);
        $rows = $collection->unique('note');
        $rows->values()->all();
        // return $rows;
        return view('admin.' . $this->route . '.index', compact('rows'));
    }
    public function store(Request $request)
    {
        $this->validate($request, $this->validation_func(1));
        $users=User::all();
        $devices=[];
        foreach ($users as $user){
            if($user->device_token==null){
                continue;
            }
            if(gettype($user->device_token)=='string'){
                $user_device_token=(array)$user->device_token;
            }else{
                $user_device_token=$user->device_token;
            }
            foreach ($user_device_token as $token){
                $devices[]= $token;
            }
            $note=new Notification();
            $note->type='admin';
            $note->collective_notice='true';
            $note->receiver_id=$user->id;
            $note->title='الإدارة';
            $note->note=$request['note'];
            $note->save();
        }
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => [
                'title'=>$request['note'],
                'sound' => 'default',
                'click_action' => 'FCM_PLUGIN_ACTIVITY',
            ],
            'data' => [
                'title'=>'الإدارة',
                'body' => $request['note'],
                'status' => 'admin',
                'type'=>'admin'
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($devices)
            ->send();
        return redirect()->route('notification.collective_notice')->with('notify', 'تم الارسال بنجاح');
    }
}
