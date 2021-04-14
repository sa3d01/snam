<?php

namespace App\Http\Controllers\Api;

use App\Models\Bank;
use App\Models\City;
use App\Models\Faq;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class CityController extends MasterController
{
    public function __construct(City $model)
    {
        $this->model = $model;
        $this->route = 'city';
        parent::__construct();
    }
    public function index(Request $request)
    {
        $rows=$this->model->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data]);
    }
    public function districts(Request $request)
    {
        $rows=$this->model->where('type','district')->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data]);
    }
    public function cities($id,Request $request)
    {
        $rows=$this->model->where(['type'=>'city','district_id'=>$id])->get();
        $data=[];
        foreach ($rows as $row){
            $arr=$row->static_model();
            $data[]=$arr;
        }
        return response()->json(['status' => 200,'data'=>$data]);
    }
}
