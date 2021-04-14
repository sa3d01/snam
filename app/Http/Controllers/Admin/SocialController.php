<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use App\Models\Ad;
use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\CityDescription;
use App\Models\Client;
use App\Models\Country;
use App\Models\Language;
use App\Models\Product;
use App\Models\Social;
use App\Models\SubCategory;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Analytics;
use Auth;
class SocialController extends MasterController
{
    public function __construct(Social $model)
    {
        $this->model = $model;
        $this->route = 'social';
        $this->module_name         = 'قائمة وسائل التواصل';
        $this->single_module_name  = 'وسيلة';
        $this->index_fields        = ['الاسم' => 'name','الرابط' => 'link'];
        $this->create_fields        = ['الاسم' => 'name','الرابط' => 'link'];
        $this->update_fields        = ['الرابط' => 'link'];
        parent::__construct();
    }
    public function validation_func()
    {
        $therulesarray = [];
//        $therulesarray['name'] = 'required';
        $therulesarray['link'] = 'required';
        return $therulesarray;
    }
    public function store(Request $request) {
        $this->validate($request, $this->validation_func());
        $social=new Social();
        $social->add_by=request('add_by');
        $social->status=request('status');
        $social->name=request('name');
        $social->link=request('link');
        $social->save();
        return redirect('admin/'.$this->route.'')->with('created', 'تمت الاضافة بنجاح');
    }
    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func());
        $social=$this->model->find($id);
        $social->status=request('status');
//        $social->name=request('name');
        $social->link=request('link');
        $social->update();
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */




}
