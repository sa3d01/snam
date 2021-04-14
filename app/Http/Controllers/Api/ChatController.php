<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\Chat;
use App\Models\Message;
use App\Models\MP3File;
use App\Models\Order;
use App\Models\Role;
use App\Models\RolePermission;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Edujugon\PushNotification\PushNotification;
use phpDocumentor\Reflection\Types\Object_;

class ChatController extends MasterController
{
    public function __construct(Chat $model)
    {
        $this->model = $model;
        $this->route = 'chat';
        parent::__construct();
    }
    public function validation_rules()
    {
        $rules = [
            'ad_id' => 'required',
           // 'receiver_id' => 'required',
            'message' => 'required',
        ];
        return $rules;
    }
    public function validation_messages()
    {
        $messsages = array(
            'ad_id.required' => 'رقم الطلب يجب ادخاله',
            'receiver_id.required' => 'رقم المستقبل يجب ادخاله',
            'message.required' => 'نص الرسالة يجب ادخاله',
        );
        return $messsages;
    }
    public function rooms(Request $request)
    {
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        if (!$user) {
            return response()->json(['status' => 401]);
        }
        $chat_ids=Chat::where('sender_id',$user->id)->orWhere('receiver_id',$user->id)->pluck('room')->unique();
        $chats=Chat::whereIn('room',$chat_ids)->get()->unique('room');
        $data=[];
        foreach ($chats as $chat){
            $arr['room'] = (integer)$chat->room;
            if ($chat->sender_id != $user->id) {
                $arr['receiver'] = $chat->sender_id;
                $arr['image'] = $chat->sender->image ? $chat->sender->image : '';
                $arr['title'] = $chat->sender->username;
            } else {
                $arr['receiver_id'] = $chat->receiver_id;
                $arr['image'] = $chat->receiver->image ? $chat->receiver->image : '';
                $arr['title'] = $chat->receiver->username;
            }
            $arr['ad'] = $chat->ad->static_model();
            $arr['last_message'] = $chat->message;
            $arr['published'] = $chat->published();
            $data[] = $arr;
        }

        return response()->json(['status' => 200,'data'=>$data]);
    }
    
        public function delete_room($room, Request $request)
    {
        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        }
        $split = explode("sa3d01", $request->header('apiToken'));
        $user = User::where('apiToken', $split['1'])->first();
        if (!$user) {
            return response()->json(['status' => 401]);
        }
        $deleting_chats = Chat::where('room', $room)->get();
        foreach ($deleting_chats as $deleting_chat) {
            $deleting_chat->delete();
        }
        $chat_ids = Chat::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->pluck('room')->unique();
        $chats = Chat::whereIn('room', $chat_ids)->get()->unique('room');
        $data = [];
        foreach ($chats as $chat) {
            $arr['ad_id'] = (integer)$chat->room;
            $arr['ad'] = $chat->ad->static_model();
            $arr['title'] = $chat->ad->title;
            $arr['image'] = $chat->ad->images ? $chat->ad->images[0] : '';
            $arr['last_message'] = $chat->message;
            $arr['published'] = $chat->published();
            $data[] = $arr;
        }
        return response()->json(['status' => 200, 'data' => $data]);
    }
    
    public function store(Request $request)
    {
        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        }
        $split = explode("sa3d01", $request->header('apiToken'));
        $user = User::where('apiToken', $split['1'])->first();
        if (!$user) {
            return response()->json(['status' => 401]);
        }
        $validator = Validator::make($request->all(), $this->validation_rules(), $this->validation_messages());
        if ($validator->passes()) {
            $all = $request->all();
            $all['sender_id'] = $user->id;
            $ad = Ad::find($request['ad_id']);
            //
            $receiver_id = $request['receiver_id'];
            if ($receiver_id == $ad->user_id) {
                $all['room'] = $request['ad_id'] . $user->id . $ad->user_id;
            } else {
                $all['room'] = $request['ad_id'] . $receiver_id . $ad->user_id;
            }
            $all['ad_id'] = $request['ad_id'];
            $all['receiver_id'] = $request['receiver_id'];
            $message = $this->model->create($all);
            //
            $title = 'أرسل' . $user->username . 'رسالة جديدة';
            $message->receiver->device_type == 'IOS' ? $not = array('title' => $title, 'sound' => 'default') : $not = null;
            $push = new PushNotification('fcm');
            $msg = [
                'notification' => array('title' => $title, 'sound' => 'default'),
                'data' => [
                    'title' => $title,
                    'body' => $title,
                    'status' => 'chat',
                    'type' => 'chat',
                    'chat' => $message->static_model()
                ],
                'priority' => 'high',
            ];
            $push->setMessage($msg)
                ->setDevicesToken($message->receiver->device_token)
                ->send();
            return response()->json(['status' => 200, 'data' => $message->static_model(), 'room' => $message->room]);
        } else {
            return response()->json(['status' => 400, 'msg' => $validator->errors()->first()]);
        }
    }

    public function chat_messages($id, Request $request)
    {
        $offset = $request->header('skip', 0);

        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        }
        $split = explode("sa3d01", $request->header('apiToken'));
        $user = User::where('apiToken', $split['1'])->first();
        if (!$user) {
            return response()->json(['status' => 401]);
        }

        $rows = Chat::where('room', $id)->orderBy('created_at', 'desc')->offset($offset)->limit(10)->latest()->get();
        $count = Chat::where('room', $id)->count();

        $data = [];
        foreach ($rows as $row) {
            $arr = $row->static_model();
            $data[] = $arr;
        }
        return response()->json(['status' => 200, 'data_count' => $count, 'data' => $data]);
    }
////
    public function userMessages(Request $request)
    {
        $validator = Validator::make($request->all(), ['user_id' => 'required']);
        if ($validator->passes()) {
            $messages = NRMessage::where('reciever_id', $request['user_id'])->latest()->get()->unique('sender_id');
            $user = User::find($request['user_id']);
            $this->update_api_token($user);
            if (count($messages) >0) {
                $data = [];
                foreach ($messages as $message) {
                    $last_reply=NRMessage::where(['sender_id'=>$request['user_id'],'reciever_id'=>$message->sender_id,'service_id'=>$message->service_id])->latest()->first();
                    if($last_reply){
                        if(Carbon::parse($last_reply->created_at) > Carbon::parse($message->created_at)){
                            continue;
                        }
                    }
                    $arr['id'] = $message->id;
                    $count = NRMessage::where(['reciever_id' => $request['user_id'], 'sender_id' => $message->sender_id])->count();
                    $arr['count'] = $count;
                    $arr['sender_id'] = $message->sender_id;
                    $arr['reciever_id'] = $message->reciever_id;
                    $arr['service_id'] = $message->service_id;
                    $arr['room_id'] = $message->room_id;
                    $arr['message'] = $message->msg;
                    if (User::where('id', $message->sender_id)->value('username') == null) {
                        $arr['sender'] = '';
                    } else {
                        $arr['sender'] = User::where('id', $message->sender_id)->value('username');
                    }
                    $service = Service::find($message->service_id);
                    $service_title = $service->title;
                    $arr['service_title'] = $service_title;
                    if ($service->Category) {
                        $arr['category'] =$service->Category->name;
                    } else {
                        $arr['category'] = 'category deleted';
                    }
                    $arr['created_date'] = $this->time($message->created_at->timestamp);
                    $data[] = $arr;
                }
                return response()->json(['value' => true, 'data' => $data]);
            } else {
                return response()->json(['value' => false, 'msg' => 'there are no messages for this user']);
            }
        } else {
            return response()->json(['value' => true, 'msg' => 'please enter user id ']);
        }

    }

    public function singleMessage(Request $request)
    {
        $validator = Validator::make($request->all(), ['message_id' => 'required']);
        if ($validator->passes()) {
            $message = NRMessage::find($request['message_id']);
            if ($message) {
                $data['id'] = $message->id;
                $data['message'] = $message->msg;
                $data['service_title'] = Service::where('id',$message->service_id)->value('title');
                $data['message_sender'] = $message->Sender->username;
                $user = User::find($message->Sender->id);
                $this->update_api_token($user);
                $data['sender_image'] = asset('images/user/' . $message->Sender->image);
                $data['message_date'] = $this->time($message->created_at->timestamp);

                return response()->json(['value' => true, 'data' => $data]);
            } else {
                return response()->json(['value' => false, 'msg' => 'there is no message with this id ']);

            }
        } else {
            return response()->json(['value' => false, 'msg' => 'please enter message id ']);
        }
    }

    public function contacts($id){
        $service_ids=Service::where('user_id',$id)->pluck('id');
        $orders=Order::whereStatus('in_progress')->where(function ($query)use($id,$service_ids) {
            $query->where('user_id',$id)
                ->orWhereIn('service_id',$service_ids);
        })->get();
        $data  = [] ;
        foreach ($orders as $order){
            $message=Message::where('order_id',$order->id)->latest()->first();
            $service=Service::find($order->service_id);
            if(!$message){
                $room=new Room();
                $room->room=str_random(10);
                $room->save();
                $message=new Message();
                $message->sender_id=$service->user_id;
                $message->reciever_id=$order->user_id;
                $message->order_id=$order->id;
                $message->room_id=$room->id;
                $message->msg='';
                $message->save();
            }
            if($id==$message->sender_id){
                $user_id=$message->reciever_id;
            }else{
                $user_id=$message->sender_id;
            }
            $user=User::find($user_id);
            $arr['user_id'] = $user_id;
            $arr['user_image'] = $user->image;
            $arr['user_name'] = $user->username;
            $arr['message'] = $message->msg;
            $arr['message_date'] = $this->time($message->created_at->timestamp);
            $arr['room_id'] = $message->room_id;
            $arr['service_id'] = $order->service_id;
            $arr['order_id'] = $order->id;
            $data[] = $arr;
        }
        return response()->json(['value'=>true,'data'=>$data]);
    }
   
}
