<?php

namespace App\Http\Controllers\Api;

use App\Models\Bank;
use App\Models\City;
use App\Models\Faq;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class BankController extends MasterController
{
    public function __construct(Bank $model)
    {
        $this->model = $model;
        $this->route = 'bank';
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
}
