<?php

namespace App\Http\Controllers\Admin;
use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\Role;
class AdController extends MasterController
{
    public function __construct(Ad $model)
    {
        $this->model = $model;
        $this->route = 'ad';
        $this->module_name         = 'قائمة الإعلانات ';
        $this->single_module_name  = 'إعلان';
        $this->index_fields        = ["العنوان" => "title","صاحب الاعلان" => "user_id","المدينة" => "city_id","القسم" => "category_id"];
        $this->create_fields       = ["العنوان" => "title","التفاصيل" => "note","صاحب الاعلان" => "user_id","المدينة" => "city_id","التواصل عبر الهاتف" => "mobile"];
        $this->show_fields         = ["العنوان" => "title","التفاصيل" => "note","تاريخ الانشاء" => "created_at","صاحب الاعلان" => "user_id","المدينة" => "city_id","القسم" => "category_id","الحالة" => "status","التواصل عبر الهاتف" => "mobile"];

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
    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $parent_categories=Category::where(['parent_id'=>null])->get();
        $parent_category_array=[];
        foreach ( $parent_categories as $category) {
            $parent_category_array[$category->id] = $category->name;
        }
        $child_category_array=Category::where(['parent_id'=>null])->first()->childs;
        $sub_child_categories_array=Category::where(['parent_id'=>null])->first()->childs->first()->childs;
        return view('admin.' . $this->route . '.create', compact('roles','permissions','parent_category_array','child_category_array','sub_child_categories_array'));
    }
//    public function store(Request $request) {
//        $all=$request->all();
//        $images=[];
//        foreach ($request->images as $image){
//            $destinationPath = 'images/ads/';
//            $filename = '1993'.$image->getClientOriginalName();
//            $image->move($destinationPath, $filename);
//            $images[]=$filename;
//        }
//        $all['images']=$images;
//        Ad::create($all);
//        return redirect('admin/' . $this->route . '')->with('created', 'تمت الاضافة بنجاح');
//    }
    public function store(Request $request) {
        $all=$request->all();
        $images=[];
        if($request->images){
            foreach ($request->images as $image){
                $destinationPath = 'images/ads/';
                $filename = '1993'.$image->getClientOriginalName();
                $image->move($destinationPath, $filename);
                $images[]=$filename;
            }
        }

        if($request['sub_child_category_id']!=null){
            $all['category_id']=$request['sub_child_category_id'];
        }elseif($request['child_category_id']!=null){
            $all['category_id']=$request['child_category_id'];
        }else{
            $all['category_id']=$request['parent_category_id'];
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
