<?php

namespace App\Http\Controllers\Api;

use App\Models\Answer;
use App\Models\Banner;
use App\Models\BannerDescription;
use App\Models\Category;
use App\Models\CategoryDescription;
use App\Models\Contact;
use App\Models\Language;
use App\Models\Notification;
use App\Models\Offer;
use App\Models\Post;
use App\Models\Rating;
use App\Models\Reason;
use App\Models\Shop;
use App\Models\ShopDescription;
use App\Models\ShopImage;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Edujugon\PushNotification\PushNotification;

class ContactController extends MasterController
{
    public function __construct(Contact $model)
    {
        $this->model = $model;
        $this->route = 'contact';
        parent::__construct();
    }
    public function reasons(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }elseif(!$check_token){
            $split = explode("sa3d01",$request->header('apiToken'));
            $user=User::where('apiToken',$split['1'])->first();
        }
        //auth
        $rows = Reason::latest()->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data]);
    }
    public function store(Request $request)
    {
        //auth
        $check_token=$this->check_apiToken($request->header('apiToken'));
        if($check_token){
            return $check_token;
        }elseif(!$check_token){
            $split = explode("sa3d01",$request->header('apiToken'));
            $user=User::where('apiToken',$split['1'])->first();
        }
        //auth
        $obj=$this->model->create($request->all());
        return response()->json(['status' => 200,'data' => $obj->static_model()]);
    }

    
}
