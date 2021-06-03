<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\Bank;
use App\Models\Category;
use App\Models\City;
use App\Models\Faq;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CategoryController extends MasterController
{
    public function __construct(Category $model)
    {
        $this->model = $model;
        $this->route = 'category';
        parent::__construct();
    }
     public function index(Request $request)
    {
        $rows = $this->model->active()->where('parent_id',null)->latest()->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            // $ads_count=0;
            // $ads_count+=Ad::where('category_id',$row->id)->count();
            // if($row->childs->count() > 0){
            //     foreach($row->childs as $child){
            //         $ads_count+=Ad::where('category_id',$child->id)->count();
            //         if($child->childs->count() > 0){
            //             foreach($child->childs as $sub_child){
            //                 $ads_count+=Ad::where('category_id',$sub_child->id)->count();
            //             }

            //         }
            //     }
            // }
            // $arr['ads_count']=$ads_count;
//            $sub_categories=[];
//            foreach($row->childs as $child){
//                $sub_categories[]=$child->static_model();
//            }
//            $arr['sub_categories']=$row->subs($row);
            $data[]=$arr;
        }
//        $ads=Ad::latest()->take(10)->get();
        $ads_array=[];
//        foreach ($ads as $ad){
//            $arr=$ad->static_model();
//            $ads_array[]=$arr;
//        }
        return response()->json(['status' => 200,'data'=>$data,'ads'=>$ads_array]);
    }
    public function show($id, Request $request)
    {
        $rows = $this->model->active()->where('parent_id',$id)->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $ads_count=0;
            $ads_count+=Ad::where('category_id',$row->id)->count();
            if($row->childs->count() > 0){
                foreach($row->childs as $child){
                    $ads_count+=Ad::where('category_id',$child->id)->count();
                    if($child->childs->count() > 0){
                        foreach($child->childs as $sub_child){
                            $ads_count+=Ad::where('category_id',$sub_child->id)->count();
                        }

                    }
                }
            }
            $arr['ads_count']=$ads_count;
            $data[]=$arr;
        }

        return response()->json(['status' => 200, 'data' => $data]);
    }
    public function ads($id,Request $request)
    {
        if($request->header('cityId') && $request->header('cityId')!=null ){
            $rows=Ad::where(['category_id'=>$id,'city_id'=>$request->header('cityId')])->get();
        }elseif ($request['title'] && $request['title']!=null) {
            $rows=Ad::where('title','LIKE','%'.$request['title'].'%')->get();
        }else{
            $rows=Ad::where('category_id',$id)->get();
        }
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data]);
    }
}
