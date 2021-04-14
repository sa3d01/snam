<?php

namespace App\Http\Controllers\Admin;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;

class AdController extends MasterController
{
    public function __construct(Ad $model)
    {
        $this->model = $model;
        $this->route = 'ad';
        $this->module_name         = 'قائمة الإعﻻنات ';
        $this->single_module_name  = 'اعﻻن';
        $this->index_fields        = ["العنوان" => "title","صاحب الاعﻻن" => "user_id","المدينة" => "city_id","القسم" => "category_id"];
        $this->create_fields       = ["العنوان" => "title","التفاصيل" => "note","صاحب الاعﻻن" => "user_id","المدينة" => "city_id","القسم" => "category_id","التواصل عبر الهاتف" => "mobile"];
        $this->show_fields         = ["العنوان" => "title","التفاصيل" => "note","تاريخ الانشاء" => "created_at","صاحب الاعﻻن" => "user_id","المدينة" => "city_id","القسم" => "category_id","الحالة" => "status","التواصل عبر الهاتف" => "mobile"];

        parent::__construct();
    }

    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['title'] ='required';
        $therulesarray['note'] ='required';
        $therulesarray['user_id'] ='required';
        $therulesarray['city_id'] ='required';
        $therulesarray['category_id'] ='required';
        return $therulesarray;
    }
    public function store(Request $request) {
        $all=$request->all();
        $images=[];
        foreach ($request->images as $image){
            $destinationPath = 'images/ads/';
            $filename = '1993'.$image->getClientOriginalName();
            $image->move($destinationPath, $filename);
            $images[]=$filename;
        }
        $all['images']=$images;
        Ad::create($all);
        return redirect('admin/' . $this->route . '')->with('created', 'تمت الاضافة بنجاح');
    }
    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func(2,$id));
        $obj=$this->model->find($id);
        $obj->update($request->all());
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }
}
