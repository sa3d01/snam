<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $row = Setting::first();

        $arr['terms'] = Page::where('name','licence')->value('content');
        $arr['about'] = Page::where('name','about')->value('content');
        $arr['blocked_ads'] = Page::where('name','block')->value('content');
        $arr['whatsapp']=$row->mobile;
        $arr['contact']=$row->contact;
        $arr['percent']=Page::where('name','percent')->value('content');
        $arr['percent_ratio']=$row->percent_ratio;
        $arr['twitter']=$row->twitter;
        $arr['instagram']=$row->instagram;
        $arr['facebook']=$row->facebook;
        $arr['snapchat']=$row->snap;
        $arr['youtube']=$row->youtube;
        return response()->json(['status' => 200, 'data' => $arr]);
    }

    public function page($name)
    {
        $page=Page::where('name',$name)->first();
        if ($name == 'healing') {
            $arr['name'] = 'the healing name';
            $arr['price'] = 0;
            return response()->json(['status' => 200, 'data' => $arr]);
        } elseif ($name == 'zakah' || $name == 'talk_about' || $name == 'hadeth' || $name == 'quran') {
            if (!$page){
                $arr['title'] = '';
                $arr['note'] = '';
                $arr['images'] = [];
            }else{
                $arr['title'] = $page->title;
                $arr['note'] = $page->content;
                $images=[];
                if ($page->images!=null){
                    $images=json_decode($page->images);
                }
                $arr['images'] =$images;
            }
            return response()->json(['status' => 200, 'data' => $arr]);
        } else {
            if (!$page){
                $arr['title'] = '';
                $arr['note'] = '';
                $arr['image'] ='';
            }else{
                $arr['title'] = $page->title;
                $arr['note'] = $page->content;
                $image=null;
                if ($page->images!=null){
                    $images=json_decode($page->images);
                    $image=$images[0];
                }
                $arr['image'] =$image;
            }
            $data[] = $arr;
            return response()->json(['status' => 200, 'data' => $data]);
        }
    }
}
