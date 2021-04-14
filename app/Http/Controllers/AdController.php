<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
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

class AdController extends Controller
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
        $categories=Category::all();
        $sliders=Slider::all();
        view()->share(array(
            'setting'=>$setting,
            'cities'=>$cities,
            'categories'=>$categories,
            'sliders'=>$sliders,
        ));
    }

    public function ads($id)
    {
        $new_ads=Ad::where(['category_id'=>$id])->orderBy('created_at','desc')->take(3)->get();
        $ads=Ad::where(['category_id'=>$id,'status'=>'active'])->get();
        $category=Category::findOrFail($id);
        $count=Ad::where(['category_id'=>$id,'status'=>'active'])->count();
        return view('web.ads',compact('ads','category','count','new_ads'));
    }
    public function create()
    {
        $sub_categories=Category::where('parent_id','!=',null)->get();
        return view('web.create_ad',compact('sub_categories'));
    }
    public function child_category($id)
    {
        $categories=Category::where('parent_id',$id)->get();
        return view('web.child_category',compact('categories'));
    }
    public function show($id)
    {
        $ad=Ad::findOrFail($id);
        $comments=Comment::where('ad_id',$id)->get();
        $similar_ads=Ad::where('category_id',$ad->category_id)->orderBy('created_at','asc')->take(1)->get();
        $count_rating=Rating::where('rated_id',$ad->user_id)->count();
        $sum_rating=Rating::where('rated_id',$ad->user_id)->sum('rate');
        $total_rating= $count_rating==0 ? 0 : round($sum_rating/$count_rating);
        return view('web.ad',compact('ad','comments','similar_ads','total_rating'));
    }
    public function store(Request $request) {
        $all=$request->all();
        $images=[];
        for ($i=1;$i<=8;$i++){
            $file = request()->file('image'.$i);
            if($file){
                $destinationPath = 'images/ads/';
                $filename = str_random(20).'.'.$file->getClientOriginalExtension();
                $file->move($destinationPath, $filename);
                $images[]=$filename;
            }
        }
        $all['images']=$images;
        $ad=Ad::create($all);
        return view('web.ad-published',compact('ad'));
    }
    public function comment(Request $request) {
        $all=$request->all();
        $all['user_id']=Auth::user()->id;
        $comment=Comment::create($all);
        $title = Auth::user()->username.' قام بترك تعليق على اعﻻنك ';
        $status='comment';
        $comment->ad->user->device_type=='IOS'? $not=array('title'=>$title, 'sound' => 'default') : $not=null;
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => $not,
            'data' => [
                'title' => $title,
                'body' => $title,
                'status' => $status,
                'type'=>'app',
                'ad'=>$comment->ad->static_model()
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($comment->ad->user->device_token)
            ->send();
        $notification=new Notification();
        $notification->sender_id=$comment->user_id;
        $notification->receiver_id=$comment->ad->user_id;
        $notification->title=$title;
        $notification->type=$status;
        $notification->ad_id=$comment->ad->id;
        $notification->save();
        return redirect()->back();
    }
    public function favourite($id) {
        $all['user_id']=Auth::user()->id;
        $all['ad_id']=$id;
        Favourite::create($all);
        return redirect()->back();
    }
    public function search(Request $request)
    {
        // return $request->all();
        if($request['text'] && $request['text']!=null){
            $query=Ad::where('title','LIKE','%'.$request['text'].'%')->where('status','active');
        }else{
            $query=Ad::where('status','active');
        }
        if($request['city_id'] && $request['city_id']!=null){
            $ads= $query->where('city_id',$request['city_id'])->orderBy('created_at','desc')->get();
        }else{
            $ads= $query->orderBy('created_at','desc')->get();
        }
//        if($request['sort']=='rate'){
//            $ads= $query->select('ads.*')->leftJoin('ratings', 'ads.id','=','ratings.ad_id')->groupBy('ads.id')->orderByRaw('SUM(rate) desc')->get();
//        }else{
//            $ads= $query->orderBy('created_at','desc')->get();
//        }
        $count=count($ads);
        $new_ads=Ad::orderBy('created_at','desc')->take(3)->get();
        return view('web.search-ads',compact('ads','count','new_ads'));
    }
}
