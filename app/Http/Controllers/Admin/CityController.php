<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\BankDescription;
use App\Models\City;
use App\Models\Language;
use App\Models\Shop;
use App\Models\ShopDescription;
use App\Models\ShopImage;
use Illuminate\Http\Request;

class CityController extends MasterController
{
    public function __construct(City $model)
    {
        $this->model = $model;
        $this->route = 'city';
        $this->module_name         = 'قائمة المدن والمناطق ';
        $this->single_module_name  = 'مدينة او منطقة';
        $this->index_fields        = ['الاسم ' => 'name'];
        $this->create_fields        = ['الاسم ' => 'name'];
        $this->update_fields        = ['الاسم ' => 'name'];
       // $this->middleware('permission:التحكم بالمدن والأحياء')->only('index');
        parent::__construct();
    }

    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['name'] ='required';
        return $therulesarray;
    }
    public function validation_msg()
    {
        $messsages = array(
            'required' => 'يجب ملئ جميع الحقول',
        );
        return $messsages;
    }
    public function index()
    {
        $rows = $this->model->where('type','city')->latest()->get();
        return view('admin.' . $this->route . '.index', compact('rows'));
    }
    public function districts()
    {
        $rows = $this->model->where('type','district')->latest()->get();
        return view('admin.' . $this->route . '.index', compact('rows'));
    }
    public function store(Request $request)
    {
        $this->validate($request, $this->validation_func(1),$this->validation_msg());
        City::create($request->all());
        return redirect('admin/' . $this->route . '')->with('created', 'تمت الاضافة بنجاح');
    }
    public function update($id, Request $request)
    {
        $this->validate($request, $this->validation_func(2, $id),$this->validation_msg());
        $obj = $this->model->find($id);
        $obj->update($request->all());
        return redirect('admin/' . $this->route . '')->with('updated', 'تم التعديل بنجاح');
    }


}
