<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use App\Models\Ad;
use App\Models\Admin;
use App\Models\Building;
use App\Models\Category;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Product;
use App\Models\SubCategory;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Analytics;
use Auth;
class ClientController extends MasterController
{
    public function __construct(Client $model)
    {
        $this->model = $model;
        $this->route = 'client';
        $this->module_name         = 'قائمة التجار';
        $this->single_module_name  = 'تاجر';
        $this->index_fields        = ['الاسم الثﻻثى' => 'name','اسم المستخدم' => 'username','البريد الإلكترونى' => 'email','رقم الجوال' => 'mobile','العنوان' => 'address'];
        $this->create_fields        = ['الاسم الثﻻثى' => 'name','اسم المستخدم' => 'username','البريد الإلكترونى' => 'email','رقم الجوال' => 'mobile','العنوان' => 'address'];
        $this->update_fields        = ['الاسم الثﻻثى' => 'name','اسم المستخدم' => 'username','البريد الإلكترونى' => 'email','رقم الجوال' => 'mobile','العنوان' => 'address'];
        parent::__construct();
    }
//    public function index() {
//        $rows = $this->model->where('type','owner')->latest()->get();
//        return view('admin.'.$this->route.'.index', compact('rows'));
//    }

    public function validation_func($method,$id=null)
    {
        if($method == 1) // POST Case
            return ['name' => 'required', 'mobile' => 'required|unique:clients', 'username' => 'required|unique:clients','email'=>'email|max:255|unique:clients', 'image' => 'mimes:png,jpg,jpeg','password'=>'required|min:6'];
        return ['name' => 'required', 'mobile' => 'required|unique:clients,mobile,'.$id, 'username' => 'required|unique:clients,username,'.$id,'email'=>'email|max:255|unique:clients,email,'.$id, 'image' => 'mimes:png,jpg,jpeg'];
    }
    public function store(Request $request) {
        $this->validate($request, $this->validation_func(1));
        $client=new Client();
        $client->add_by=request('add_by');
        $client->status=request('status');
        $client->name=request('name');
        $client->username=request('username');
        $client->mobile=request('mobile');
        $client->address=request('address');
        $client->password=request('password');
        $client->email=request('email');
        $client->save();
        return redirect('admin/'.$this->route.'')->with('created', 'تمت الاضافة بنجاح');
    }
    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func(2,$id));
        $client=$this->model->find($id);
//        $client->status=request('status');
        $client->name=request('name');
        $client->username=request('username');
        $client->mobile=request('mobile');
        $client->address=request('address');
        $client->password=request('password');
        $client->email=request('email');
        $client->update();
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }



}
