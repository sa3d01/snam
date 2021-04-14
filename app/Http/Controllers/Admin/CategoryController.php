<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\CategoryDescription;
use App\Models\Language;
use Illuminate\Http\Request;

class CategoryController extends MasterController
{
    public function __construct(Category $model)
    {
        $this->model = $model;
        $this->route = 'category';
        $this->module_name         = 'قائمة الأقسام ';
        $this->single_module_name  = 'قسم';
        $this->index_fields        = ["الإسم" => "name","القسم الرئيسي" => "parent_id","الصورة" => "image"];
        parent::__construct();
    }

    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['name'] ='required';
        return $therulesarray;
    }
    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func(2,$id));
        $obj=$this->model->find($id);
        $obj->update($request->all());
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }
    public function store( Request $request) {
        $this->validate($request, $this->validation_func(1));
        $obj=$this->model->create($request->all());
        return redirect('admin/'.$this->route.'')->with('created','تم الانشاء بنجاح');
    }

}
