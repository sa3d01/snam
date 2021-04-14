<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Chat;
use App\Models\Comment;
use App\Models\Contact;
use App\Models\Favourite;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\Setting;
use App\Models\Bank;
use App\Models\Slider;
use App\User;
use App\Models\City;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Auth;
use Session;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $setting=Setting::first();
        $cities=City::where('status','active')->get();
        $sliders=Slider::all();
        view()->share(array(
            'setting'=>$setting,
            'cities'=>$cities,
            'sliders'=>$sliders,
        ));
    }
    public static function lang(){
        if (session()->has('lang')) {
            if (session()->get('lang') == 'ar') {
                App::setlocale('ar');
                $lang='ar';
            }else{
                App::setlocale('en');
                $lang='en';
            }
        }else{
            $lang='ar';
            App::setlocale('ar');
        }

        return $lang;
    }
    public function index()
    {
        $banks=Bank::all();
        $new_ads=Ad::orderBy('created_at','desc')->take(6)->get();
        $categories=Category::where('parent_id',null)->get();
        return view('web.index',compact('banks','new_ads','categories'));
    }
    public function redirect_note($type,$id=null)
    {
        $note=Notification::find($id);
        $note->read='1';
        $note->update();
        if($type=='chat'){
            $room=Chat::where(['sender_id'=>$note->sender_id,'receiver_id'=>$note->receiver_id,'ad_id'=>$note->ad_id])->value('room');
            return redirect('/chat-single/'.$room);
        }elseif ($type=='rate'){
            return redirect('/profile');
        }else{
            return redirect('ad/'.$note->ad_id);
        }
    }
    public function profile()
    {
        $user_id=Auth::user()->id;
        $count_rating=Rating::where('rated_id',$user_id)->count();
        $sum_rating=Rating::where('rated_id',$user_id)->sum('rate');
        $total_rating= $count_rating==0 ? 0 : round($sum_rating/$count_rating);
        $ads=Ad::where('user_id',$user_id)->get();
        $rates=Rating::where('rated_id',$user_id)->get();
        $favourites=Favourite::where('user_id',$user_id)->get();
        return view('web.profile',compact('total_rating','ads','rates','favourites'));
    }
    public function seller_profile($id)
    {
        $user=User::find($id);
        $count_rating=Rating::where('rated_id',$id)->count();
        $sum_rating=Rating::where('rated_id',$id)->sum('rate');
        $total_rating= $count_rating==0 ? 0 : round($sum_rating/$count_rating);
        $ads=Ad::where('user_id',$id)->get();
        $rates=Rating::where('rated_id',$id)->get();
        $favourites=Favourite::where('user_id',$id)->get();
        return view('web.seller-profile',compact('total_rating','ads','rates','favourites','user'));
    }
    public function licence()
    {
        return view('web.about');
    }
    public function chat()
    {
        $user=Auth::user();
        $chat_ids=Chat::where('sender_id',$user->id)->orWhere('receiver_id',$user->id)->pluck('room')->unique();
        $chats=Chat::whereIn('room',$chat_ids)->get()->unique('room');
        if(count($chat_ids)>0){
            $messages=Chat::where(['room'=>$chat_ids[0]])->get();
        }
        return view('web.chat',compact('chats','messages'));
    }
    public function notification()
    {
        $note_comments=Notification::where(['receiver_id'=>Auth::id(),'type'=>'comment','read'=>'0'])->get();
        $note_chats=Notification::where(['receiver_id'=>Auth::id(),'type'=>'chat','read'=>'0'])->get();
        $note_rates=Notification::where(['receiver_id'=>Auth::id(),'type'=>'rate','read'=>'0'])->get();
        $note_live_times=Notification::where(['receiver_id'=>Auth::id(),'type'=>'live_time','read'=>'0'])->get();
        $note_blocks=Notification::where(['receiver_id'=>Auth::id(),'type'=>'block','read'=>'0'])->get();
        return view('web.notification',compact('note_comments','note_blocks','note_chats','note_live_times','note_rates'));
    }
    public function new_chat($id)
    {
        $ad=Ad::find($id);
        $new_chat= Chat::where(['sender_id'=>Auth::id(),'receiver_id'=>$ad->user_id,'ad_id'=>$id])->first();
        if(!$new_chat){
            $new_chat=new Chat();
            $new_chat->sender_id=Auth::id();
            $new_chat->receiver_id=$ad->user->id;
            $new_chat->ad_id=$id;
            $new_chat->message=' السﻻم عليكم';
            $new_chat->room=$ad->id.Auth::id().$ad->user_id;
            $new_chat->save();
            $new_chat->refresh();
        }
        $chat_ids=Chat::where('sender_id',Auth::id())->orWhere('receiver_id',Auth::id())->pluck('room');
        $chats=Chat::whereIn('room',$chat_ids)->get()->unique('room');
        $messages=Chat::where(['sender_id'=>Auth::id(),'ad_id'=>$id])->get();
        return view('web.chat',compact('chats','messages','receiver_id'));
    }
    public function single_chat($id)
    {
        $user=Auth::user();
        $chat_ids=Chat::where('sender_id',$user->id)->orWhere('receiver_id',$user->id)->pluck('room')->unique();
        $chats=Chat::whereIn('room',$chat_ids)->get()->unique('room');
        if(count($chat_ids)>0){
            $messages=Chat::where(['room'=>$id])->get();
        }
        $room=$id;
        return view('web.chat',compact('chats','messages','room'));
    }
    public function chat_store(Request $request)
    {
        $latest_chat=Chat::where('room',$request->room)->first();
        $chat = new Chat();
        $chat->message = $request->message;
        $chat->room = $latest_chat->room;
        $chat->ad_id = $latest_chat->ad_id;
        $chat->sender_id = Auth::user()->id;
        if($latest_chat->sender_id!=Auth::user()->id){
            $receiver_id=$latest_chat->sender_id;
        }else{
            $receiver_id=$latest_chat->receiver_id;
        }
        $chat->receiver_id = $receiver_id;
        $chat->save();
        $title = ' أرسل  '.Auth::user()->username.' رسالة جديدة  ';
        $note =  ' أرسل  '.Auth::user()->username.' رسالة جديدة  ';
        $chat->receiver->device_type=='IOS'? $not=array('title'=>$title, 'sound' => 'default') : $not=null;
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => $not,
            'data' => [
                'title' => $title,
                'body' => $note,
                'status' => 'chat',
                'type'=>'chat',
                'ad'=>$chat->ad->static_model()
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($chat->receiver->device_token)
            ->send();
        $notification=new Notification();
        $notification->title=$title;
        $notification->sender_id=Auth::user()->id;
        $notification->receiver_id=$receiver_id;
        $notification->ad_id=$latest_chat->ad_id;
        $notification->type='chat';
        $notification->save();
        return redirect('/chat-single/'.$latest_chat->room);
    }
    public function edit_profile()
    {
        return view('web.edit-profile');
    }
    public function update_profile(Request $request)
    {
        $user=User::find($request['user_id']);
        $user->update($request->all());
        $count_rating=Rating::where('rated_id',$user->id)->count();
        $sum_rating=Rating::where('rated_id',$user->id)->sum('rate');
        $total_rating= $count_rating==0 ? 0 : round($sum_rating/$count_rating);
        $ads=Ad::where('user_id',$user->id)->get();
        $rates=Rating::where('rating_id',$user->id)->get();
        $favourites=Favourite::where('user_id',$user->id)->get();
        return view('web.profile',compact('total_rating','ads','rates','favourites'));
    }
    public function contact(Request $request)
    {
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->message = $request->input('message');
        $contact->save();
        return redirect('/')->with('created', 'تمت الارسال بنجاح');
    }
    public function rate(Request $request) {
        $all=$request->all();
        $all['rating_id']=Auth::user()->id;
        $rate=Rating::create($all);
        $title = Auth::user()->username.' قام بتقييمك';
        $status='rate';
        $rate->rated->device_type=='IOS'? $not=array('title'=>$title, 'sound' => 'default') : $not=null;
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => $not,
            'data' => [
                'title' => $title,
                'body' => $title,
                'status' => $status,
                'type'=>'app',
                'ad'=>''
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($rate->rated->device_token)
            ->send();
        $notification=new Notification();
        $notification->sender_id=Auth::user()->id;
        $notification->receiver_id=$rate->rated_id;
        $notification->title=$title;
        $notification->type=$status;
        $notification->save();
        return redirect()->back();
    }

}
