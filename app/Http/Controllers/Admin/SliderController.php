<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\BankDescription;
use App\Models\Language;
use App\Models\Shop;
use App\Models\ShopDescription;
use App\Models\ShopImage;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends MasterController
{
    public function __construct(Slider $model)
    {
        $this->model = $model;
        $this->route = 'slider';
        $this->module_name         = 'قائمة اعﻻنات الاسليدر ';
        $this->single_module_name  = 'بنر';
        $this->index_fields        = [' صورة البنر' => 'image'];
        $this->create_fields        = [' صورة البنر' => 'image'];
        $this->update_fields        = [' صورة البنر' => 'image'];
        parent::__construct();
    }
    public function store(Request $request) {
        $this->validate($request, $this->validation_func(1));
        $this->model->create($request->all());
        return redirect('admin/'.$this->route.'')->with('created', 'تمت الاضافة بنجاح');
    }
    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func(2,$id));
        $obj=$this->model->find($id);
        $obj->update($request->all());
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }
    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['image'] ='required';
        return $therulesarray;
    }
}
