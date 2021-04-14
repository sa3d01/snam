<?php

namespace App\Http\Controllers\Api;

use App\Models\Answer;
use App\Models\Banner;
use App\Models\BannerDescription;
use App\Models\Category;
use App\Models\CategoryDescription;
use App\Models\Language;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Order;
use App\Models\Post;
use App\Models\Rating;
use App\Models\Shop;
use App\Models\ShopDescription;
use App\Models\ShopImage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Edujugon\PushNotification\PushNotification;

class ShopController extends MasterController
{
    public function __construct(Shop $model,Order $related_model)
    {
        $this->model = $model;
        $this->route = 'shops';
        $this->apiToken = true;
        $this->index_fields        =  ['id','lat','long','mobile','email','logo'];
        $this->json_fields        = ['name','address'];
        $this->related_model = $related_model;
        $this->related_model_name = 'orders';
        parent::__construct();
    }

    public function category_shops($id,Request $request)
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
        $count = $this->model->active()->where('category_id',$id)->count();
        $perPage = 10;
        $offset = ($page * $perPage) - $perPage;
        $rows = $this->model->active()->latest()->where('category_id',$id)->offset($offset)->limit(10)->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $arx=[];
            $orders=Order::where(['shop_id'=>$row->id,'client_id'=>$user->id,'status'=>'waiting'])->get();
            foreach ($orders as $order){
                $arx[]=$order->static_model();
            }
            $arr['orders'] =$arx;
            $data[]=$arr;
        }
        if($count>0){
            $pages_count=(integer)round($count/10);
            if($pages_count==0){
                $pages_count=1;
            }
        }else{
            $pages_count=0;
        }
        return response()->json(['value' => true,'data_count'=>$count,'pages_count'=>$pages_count,'data'=>$data,'apiToken'=>$user->apiToken]);
    }
}
