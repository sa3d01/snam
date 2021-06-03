<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Object_;
class CategoryController extends MasterController
{
    public function __construct(Category $model)
    {
        $this->model = $model;
        $this->route = 'category';
        $this->module_name         = 'قائمة الأقسام ';
        $this->single_module_name  = 'قسم';
        $this->index_fields        = ["الإسم" => "name","القسم الرئيسي" => "parent_id","الصورة" => "image"];
        $this->create_fields        = ["الإسم" => "name","الصورة" => "image"];
        parent::__construct();
    }

    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['name'] ='required';
        return $therulesarray;
    }
//    public function update($id, Request $request) {
//        $this->validate($request, $this->validation_func(2,$id));
//        $obj=$this->model->find($id);
//        $obj->update($request->all());
//        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
//    }
//    public function store( Request $request) {
//        $this->validate($request, $this->validation_func(1));
//        $obj=$this->model->create($request->all());
//        return redirect('admin/'.$this->route.'')->with('created','تم الانشاء بنجاح');
//    }

    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func(2,$id));
        $obj=$this->model->find($id);
        $all=$request->all();
        if($request['child_category_id']!=null || $request['child_category_id']!=''){
            $all['parent_id']=$request['child_category_id'];
        }elseif($request['parent_category_id']!=null || $request['parent_category_id']!=''){
            $all['parent_id']=$request['parent_category_id'];
        }else{
            $all['parent_id']=null;
        }
        $obj->update($all);
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }
    public function store( Request $request) {
        $this->validate($request, $this->validation_func(1));
        $all=$request->all();
        if($request['child_category_id']!=null || $request['child_category_id']!=''){
            $all['parent_id']=$request['child_category_id'];
        }elseif($request['parent_category_id']!=null || $request['parent_category_id']!=''){
            $all['parent_id']=$request['parent_category_id'];
        }else{
            $all['parent_id']=null;
        }
        $this->model->create($all);
        return redirect('admin/'.$this->route.'')->with('created','تم الانشاء بنجاح');
    }

    public function get_category_childs($id)
    {
        $cat=Category::find($id);
        if($cat->childs){
            $childs = $cat->childs;
        }else{
            $childs=new Object_();
        }
        return response()->json($childs);
    }

}
