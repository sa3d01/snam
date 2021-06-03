<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
    public function editPage($name)
    {
        $page=Page::where('name',$name)->first();
        return View('admin.page.edit',compact('page'));
    }
    public function update($id, Request $request) {
        $page=$this->model->find($id);
        $data=$request->all();
        if ($request->images){
            foreach ($request->images as $image){
                if (is_file($image)) {
                    if ($image->getSize() > 4194304){
                        return redirect()->back()->withErrors(['حجم الصورة كبير جدا..']);
                    }
                    $filename = Str::random(10) . '.' . $image->getClientOriginalExtension();
                    $image->move('images/page/', $filename);
                    $local_name=asset('images/page/').'/'.$filename;
                }else {
                    $local_name = $image;
                }
                $images[]=$local_name;
            }
            $data['images'] = $images;
        }

        $page->update($data);
        return redirect('admin/page/'.$page->name)->with('updated','تم التعديل بنجاح');
    }



}
