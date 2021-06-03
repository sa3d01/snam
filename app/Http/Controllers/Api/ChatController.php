<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\Chat;
use App\User;
use Auth;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Validator;

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
        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        }
        $split = explode("sa3d01", $request->header('apiToken'));
        $user = User::where('apiToken', $split['1'])->first();
        if (!$user) {
            return response()->json(['status' => 401]);
        }
        $chat_ids = Chat::where('sender_id', $user->id)->orWhere('receiver_id', $user->id)->pluck('room')->unique();
        $chats = Chat::whereIn('room', $chat_ids)->get()->unique('room');
        $data = [];
        foreach ($chats as $chat) {
            $arr['room'] = (integer)$chat->room;
            if ($chat->sender_id != $user->id) {
                $arr['receiver_id'] = $chat->sender_id;
                $arr['image'] = $chat->sender->image ? $chat->sender->image : '';
                $arr['title'] = $chat->sender->username;
            } else {
                $arr['receiver_id'] = $chat->receiver_id;
                $arr['image'] = $chat->receiver->image ? $chat->receiver->image : '';
                $arr['title'] = $chat->receiver->username;
            }
            $arr['ad'] = $chat->ad->simple_static_model();
            $arr['last_message'] = $chat->message;
            $arr['published'] = $chat->published();
            $data[] = $arr;
        }

        return response()->json(['status' => 200, 'data' => $data]);
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
            $title = '  أرسل  ' . $user->username . '  رسالة جديدة';
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
}
