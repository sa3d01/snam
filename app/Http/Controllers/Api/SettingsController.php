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
use App\Models\Post;
use App\Models\Setting;
use App\Models\Rating;
use App\Models\Shop;
use App\Models\ShopDescription;
use App\Models\ShopImage;
use App\Models\Social;
use App\Models\SubCategory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Edujugon\PushNotification\PushNotification;

class SettingsController extends Controller
{
    public function index()
    {
        $row = Setting::first();
//        $arr['licence']=$row->licence;
        $arr['about']=$row->about;
//        $arr['block']=$row->block;
//        $arr['zakah']=$row->zakah;
//        $arr['quran']=$row->quran;
//        $arr['hadeth']=$row->hadeth;
//        $arr['talk_about']=$row->talk_about;
//        $arr['festival']=$row->festival;

        $arr['whatsapp']=$row->mobile;
        $arr['contact']=$row->contact;
        $arr['percent']=$row->percent;
        $arr['percent_ratio']=$row->percent_ratio;
        $arr['twitter']=$row->twitter;
        $arr['instagram']=$row->instagram;
        $arr['facebook']=$row->facebook;
        $arr['snapchat']=$row->snap;
        $arr['youtube']=$row->youtube;
        return response()->json(['status' => 200, 'data' => $arr]);
    }
    public function page($name){
        $row = Setting::first();
        $content=$row->$name;
        return view('web.pages.'.$name,compact('content'));
    }
}
