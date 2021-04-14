<?php

namespace App\Http\Controllers\Api;

use App\Models\Article;
use App\Models\City;
use App\Models\Faq;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ArticleController extends MasterController
{
    public function __construct(Article $model)
    {
        $this->model = $model;
        $this->route = 'article';
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
    public function show($id,Request $request)
    {
        $row=$this->model->find($id);
        return response()->json(['status' => 200,'data'=>$row->static_model()]);
    }
}
