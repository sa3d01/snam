<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\Category;
use App\Models\Chat;
use App\Models\City;
use App\Models\Comment;
use App\Models\Favourite;
use App\Models\Notification;
use App\Models\Rating;
use App\Models\Report;
use App\Models\Slider;
use App\User;
use Edujugon\PushNotification\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class AdController extends MasterController
{
    public function __construct(Ad $model)
    {
        $this->model = $model;
        $this->route = 'ad';
        parent::__construct();
    }
    public function index(Request $request)
    {
        $offset = $request->header('skip', 0);
        if($request['category_id'] && $request['city_id']){
            if (City::where('id',$request['city_id'])->value('type')=='district'){
                $cities_ids=City::where('district_id',$request['city_id'])->pluck('id');
                $rows=Ad::whereIn('city_id',$cities_ids)->where('category_id',$request['category_id'])->latest()->offset($offset)->limit(10)->get();
            }else{
                $rows=Ad::where(['category_id'=>$request['category_id'],'city_id'=>$request['city_id']])->latest()->offset($offset)->limit(10)->get();
            }
            $count = Ad::where(['category_id'=>$request['category_id'],'city_id'=>$request['city_id']])->latest()->count();
        }elseif ($request['title']) {
            $rows=Ad::where('title','LIKE','%'.$request['title'].'%')->latest()->offset($offset)->limit(10)->get();
            $count = Ad::where('title','LIKE','%'.$request['title'].'%')->latest()->count();
        }elseif ($request['city_id']) {
            if (City::where('id',$request['city_id'])->value('type')=='district'){
                $cities_ids=City::where('district_id',$request['city_id'])->pluck('id');
                $rows=Ad::whereIn('city_id',$cities_ids)->latest()->offset($offset)->limit(10)->get();
            }else{
                $rows=Ad::where(['city_id'=>$request['city_id']])->latest()->offset($offset)->limit(10)->get();
            }
            $count = Ad::where('title','LIKE','%'.$request['title'].'%')->latest()->count();
        }elseif ($request['category_id']) {
            $rows=Ad::where(['category_id'=>$request['category_id']])->latest()->offset($offset)->limit(10)->get();
            $count = Ad::where(['category_id'=>$request['category_id']])->latest()->count();
        }else{
            $rows=Ad::latest()->offset($offset)->limit(10)->get();
            $count = Ad::latest()->count();
        }
        $data=[];
        foreach ($rows as $row){
            $arr = $row->simple_static_model();
            $arr['district'] = $row->city ? $row->city->district->static_model() : null;
            $arr['is_favourite']=false;
            if($request->header('apiToken')){
                $check_token=$this->check_apiToken($request->header('apiToken'));
                if($check_token && $this->apiToken == true){
                    return $check_token;
                }
                $split = explode("sa3d01",$request->header('apiToken'));
                $user=User::where('apiToken',$split['1'])->first();
                $fav=Favourite::where(['ad_id'=>$row->id,'user_id'=>$user->id])->first();
                $arr['is_favourite']=$fav?true:false;
                $chat=Chat::where(['ad_id'=>$row->id,'sender_id'=>$user->id])->orWhere(['ad_id'=>$row->id,'receiver_id'=>$user->id])->first();
                if($chat){
                    $arr['ad_id']=$chat->room;
                }
            }
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data_count'=>$count,'data'=>$data]);
    }
    public function slider(Request $request)
    {
        $rows=Slider::all();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data]);
    }
    public function add_comment(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        //auth
        $comment=new Comment();
        $comment->user_id=$user->id;
        $comment->ad_id=$request['ad_id'];
        $comment->comment=$request['comment'];
        $comment->save();

        $title = $user->username.' ترك تعليقا على اعلانك';
        $status='comment';
        $ad=Ad::find($request['ad_id']);
        $ad->user->device_type=='IOS'? $not=array('title'=>$title, 'sound' => 'default') : $not=null;
        $push = new PushNotification('fcm');
        $msg = [
            'notification' => $not,
            'data' => [
                'title' => $title,
                'body' => $title,
                'status' => $status,
                'type'=>'app',
                'ad'=>$ad->static_model()
            ],
            'priority' => 'high',
        ];
        $push->setMessage($msg)
            ->setDevicesToken($ad->user->device_token)
            ->send();
        $notification=new Notification();
        $notification->sender_id=$user->id;
        $notification->receiver_id=$ad->user->id;
        $notification->ad_id=$request['ad_id'];
        $notification->title=$title;
        $notification->type=$status;
        $notification->save();
        $row=$this->model->find($request['ad_id']);
        $arr=$row->static_model();
        $fav=Favourite::where(['ad_id'=>$row->id,'user_id'=>$user->id])->first();
        $arr['is_favourite']=$fav?true:false;
        return response()->json(['status' => 200,'data'=>$arr]);
    }
    public function delete_comment($id,Request $request)
    {
        $comment=Comment::find($id);
        if (!$comment){
            return response()->json(['status' => 400, 'msg' => 'not found'],400);
        }
        $ad=$comment->ad;
        $comment->delete();
        return response()->json(['status' => 200, 'data' => $ad->static_model()]);
    }
    public function rate(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        //auth
        $rate=new Rating();
        $rate->rating_id=$user->id;
        $rate->rated_id=$request['rated_id'];
        $rate->comment=$request['comment'];
        $rate->rate=$request['rate'];
        $rate->save();
        $arr=$rate->static_model();
        return response()->json(['status' => 200,'data'=>$arr]);
    }
    public function upload_multi_files(Request $request)
    {
        $validate = Validator::make($request->all(),
            [
                'file' => 'required',
                // 'file.*' => 'image|mimes:jpeg,jp g,png,jpg,gif,svg|max:6048'
            ]
        );
        if ($validate->fails()) {
            return response()->json(['status' => 400, 'msg' => $validate->errors()->first()],400);
        }
        $data = [];
        for ($i = 0; $i < count($request['file']); $i++) {
            $file = $request['file'][$i];
            $destinationPath = 'images/ads/';
            $filename = Str::random(10) . '.' . $file->getClientOriginalExtension();
            $file->move($destinationPath, $filename);
            $data[] = asset($destinationPath) . '/' . $filename;
        }
        return response()->json(['status' => 200, 'data' => $data]);
    }
    public function store(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        //auth
        $category=Category::find($request['category_id']);
        if (!$category){
            return response()->json(['status' => 400, 'msg' => 'يوجد مشكلة فى هذا التصنيف جاري معالجتها حاليا ..']);
        }
        $all=$request->all();
        $all['user_id']=$user->id;
        $row=$this->model->create($all);
        $row->refresh();
        $arr=$row->static_model();
        $fav=Favourite::where(['ad_id'=>$row->id,'user_id'=>$user->id])->first();
        $arr['is_favourite']=$fav?true:false;
        return response()->json(['status' => 200,'data'=>$arr]);
    }
    public function update($id,Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        //auth
        $ad=$this->model->find($id);
        if($ad->user_id!=$user->id){
            return response()->json(['status' => 400]);
        }
        $all=$request->all();
        $all['user_id']=$user->id;
        if($request->images && $request['removed_images']){
            $images=[];
            foreach ($request->images as $image){
                $destinationPath = 'images/ads/';
                $filename = $image->getClientOriginalName();
                $image->move($destinationPath, $filename);
                $images[]=$filename;
            }
            if(gettype($ad->images)=='string'){
                $current_images=(array)$ad->images;
            }else{
                $current_images=$ad->images;
            }
            foreach ($current_images as $current_image){
                if(in_array($current_image,$request['removed_images'])){
                    continue;
                }else{
                    $images[]=$current_image;
                }
            }
            $all['images']=$images;
        }

        if($request->videos && $request['removed_videos']){
            $videos=[];
            foreach ($request->videos as $video){
                $destinationPath = 'images/ads/';
                $filename = $video->getClientOriginalName();
                $video->move($destinationPath, $filename);
                $videos[]=$filename;
            }
            if(gettype($ad->videos)=='string'){
                $current_videos=(array)$ad->videos;
            }else{
                $current_videos=$ad->videos;
            }
            foreach ($current_videos as $current_video){
                if(in_array($current_video,$request['removed_videos'])){
                    continue;
                }else{
                    $videos[]=$current_video;
                }
            }
            $all['videos']=$videos;
        }

        $ad->update($all);
        $ad->refresh();
        $arr=$ad->static_model();
        $fav=Favourite::where(['ad_id'=>$ad->id,'user_id'=>$user->id])->first();
        $arr['is_favourite']=$fav?true:false;
        return response()->json(['status' => 200,'data'=>$arr]);
    }
    public function add_favourite(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        //auth
        $favourite=Favourite::where('ad_id',$request['ad_id'])->first();
        if($favourite){
            $favourite->delete();
        }else{
            $favourite=new Favourite();
            $favourite->user_id=$user->id;
            $favourite->ad_id=$request['ad_id'];
            $favourite->save();
        }
        $favourite=Favourite::where('user_id',$user->id)->pluck('ad_id');
        $ads = Ad::whereIn('id', $favourite)->get();
        $data = [];
        foreach ($ads as $ad) {
            $arr = $ad->static_model();
            $fav = Favourite::where(['ad_id' => $ad->id, 'user_id' => $user->id])->first();
            $arr['is_favourite'] = $fav ? true : false;
            $data[] = $arr;
        }
        return response()->json(['status' => 200, 'data' => $data]);
    }

    public function add_report(Request $request)
    {
        //auth
        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        }
        $split = explode("sa3d01", $request->header('apiToken'));
        $user = User::where('apiToken', $split['1'])->first();
        //auth
        $report = Report::where('ad_id', $request['ad_id'])->first();
        if($report){
            return response()->json(['status' => 400,'msg'=>'تم التبليغ من قبل']);
        }else{
            $report=new Report();
            $report->user_id=$user->id;
            $report->ad_id=$request['ad_id'];
            $report->report=$request['report'];
            $report->save();
        }
        $row=$this->model->find($request['ad_id']);
        $arr=$row->static_model();
        $fav=Report::where(['ad_id'=>$row->id,'user_id'=>$user->id])->first();
        $arr['is_reported']=$fav?true:false;
        return response()->json(['status' => 200,'data'=>$arr]);
    }
    public function get_favourite(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        //auth
        $favourite=Favourite::where('user_id',$user->id)->pluck('ad_id');
        $ads=Ad::whereIn('id',$favourite)->get();
        $data=[];
        foreach ($ads as $ad){
            $arr=$ad->static_model();
            $fav=Favourite::where(['ad_id'=>$ad->id,'user_id'=>$user->id])->first();
            $arr['is_favourite']=$fav?true:false;
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data]);
    }
    public function show($id, Request $request)
    {
        $row = $this->model->find($id);
        if (!$row) {
            return response()->json(['value' => false]);
        }
        $arr=$row->static_model();
        $arr['is_favourite']=false;
        if($request->header('apiToken')){
            $check_token=$this->check_apiToken($request->header('apiToken'));
            if($check_token && $this->apiToken == true){
                return $check_token;
            }
            $split = explode("sa3d01",$request->header('apiToken'));
            $user=User::where('apiToken',$split['1'])->first();
            $fav=Favourite::where(['ad_id'=>$id,'user_id'=>$user->id])->first();
            $arr['is_favourite']=$fav?true:false;
        }
        $rows=Ad::where('id','!=',$id)->where('category_id',$row->category_id)->latest()->limit(10)->get();
        $similar=[];
        foreach($rows as $ad){
            $arrs=$ad->static_model();
            $arrs['is_favourite']=false;
            $similar[]=$arrs;
        }
        $arr['similar_ads']=$similar;

        return response()->json(['status' => 200, 'data' => $arr]);
    }
    public function destroy($id,Request $request) {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }
        //auth
        $ad=$this->model->find($id);
        if(!$ad){
            return response()->json(['status' => 400]);
        }
        $split = explode("sa3d01",$request->header('apiToken'));
        $user=User::where('apiToken',$split['1'])->first();
        if($ad->user_id!=$user->id){
            return response()->json(['status' => 400]);
        }
        $this->model->find($id)->delete();
        return response()->json(['status' => 200]);
    }
}
