<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use App\Models\Page;
use App\Models\Language;
use App\Models\PageDescription;
use App\Models\SubPage;
use Illuminate\Http\Request;
use Analytics;
use Auth;
class PageController extends MasterController
{
    public function __construct(Page $model)
    {
        $this->model = $model;
        $this->route = 'page';
        $this->module_name         = 'قائمة الصفحة';
        $this->single_module_name  = 'صفحة';
        parent::__construct();
    }

    public function validation_func()
    {
        $languages=Language::all();
        $therulesarray = [];
        foreach ( $languages as $language) {
            $therulesarray['name_'.$language->label] = 'required';
            $therulesarray['description_'.$language->label] = 'required';
        }
        return $therulesarray;
    }
    public function store(Request $request) {
        $this->validate($request, $this->validation_func());
        $page=new Page();
        $page->add_by=request('add_by');
        $page->status=request('status');
        $page->save();
        $languages=Language::all();
        foreach ($languages as $language){
            $page_description=new PageDescription();
            $page_description->name=request('name_'.$language->label);
            $page_description->description='<center>'.request('description_'.$language->label).'</center>';
            $page_description->language_id=$language->id;
            $page_description->page_id=$page->id;
            $page_description->save();
        }
        return redirect('admin/'.$this->route.'')->with('created', 'تمت الاضافة بنجاح');
    }

    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func());
        $page=$this->model->find($id);
        $page->status=request('status');
        $page->update();
        $languages=Language::all();
        foreach ($languages as $language){
            $page_description=PageDescription::where(['page_id'=>$id,'language_id'=>$language->id])->first();
            if(isset($page_description)){
                $page_description->name=request('name_'.$language->label);
                $page_description->description='<center>'.request('description_'.$language->label).'</center>';
                $page_description->update();
            }else{
                $page_description=new PageDescription();
                $page_description->name=request('name_'.$language->label);
                $page_description->description='<center>'.request('description_'.$language->label).'</center>';
                $page_description->language_id=$language->id;
                $page_description->page_id=$page->id;
                $page_description->save();
            }

        }
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }



}
