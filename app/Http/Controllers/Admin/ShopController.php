<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends MasterController
{
    public function __construct(Shop $model)
    {
        $this->model = $model;
        $this->language = '2';
        $this->route = 'shop';
        $this->module_name         = 'قائمة المتاجر ';
        $this->single_module_name  = 'متجر';
        $this->index_fields        = ['الصورة المعبرة' => 'logo','القسم المنتمى اليه' => 'category_id','رقم الهاتف' => 'mobile','البريد الإلكترونى' => 'email'];
        $this->json_fields        = ["الاسم" => "name","الوصف"=>"note"];
        parent::__construct();
    }

    public function validation_func($method,$id=null)
    {
        $languages=Language::all();
        $therulesarray = [];
        foreach ( $languages as $language) {
            $therulesarray['name_'.$language->label] = 'required';
            $therulesarray['note_'.$language->label] = 'required';
        }
        $therulesarray['category_id'] ='required';
        $therulesarray['mobile'] ='required|unique:shops';
        $therulesarray['email'] ='required|unique:shops';
        $therulesarray['logo'] ='mimes:png,jpg,jpeg';
        if($method==1){
            return $therulesarray;
        }else{
            $therulesarray = [];
            foreach ( $languages as $language) {
                $therulesarray['name_'.$language->label] = 'required';
                $therulesarray['note_'.$language->label] = 'required';
            }
            $therulesarray['logo'] ='mimes:png,jpg,jpeg';
            $therulesarray['email'] ='required|email|max:255|unique:shops,email,'.$id;
            $therulesarray['mobile'] ='required|unique:shops,mobile,'.$id;
            return $therulesarray;
        }
    }
}
