<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use App\Models\Ad;
use App\Models\Admin;
use App\Models\Bank;
use App\Models\Category;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Product;
use App\Models\SubCategory;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Analytics;
use Auth;
class UserController extends MasterController
{
    public function __construct(User $model)
    {
        $this->model = $model;
        $this->route = 'user';
        $this->module_name         = 'قائمة الأعضاء';
        $this->single_module_name  = 'عضو';
        $this->index_fields        = ['الاسم' => 'username','رقم الجوال' => 'mobile','المدينة' => 'city_id'];
        $this->create_fields        = ['الاسم' => 'username',' كلمة المرور' => 'password','رقم الجوال' => 'mobile','المدينة' => 'city_id','الصورة الشخصية' => 'image'];
        $this->update_fields        = ['الاسم' => 'username','رقم الجوال' => 'mobile','المدينة' => 'city_id'];
        parent::__construct();
    }
    public function Activate($id)
    {
        $row = $this->model->find($id);
        if($row->approved == 'false') {
            $this->model->find($id)->update(['approved' => 'true']);
            return back()->with('success', 'تم التفعيل بنجاح');
        }else{
            $this->model->find($id)->update(['approved' => 'false']);
            return back()->with('success', 'تم الغاء التفعيل بنجاح');
        }
    }

    public function active_users()
    {
        $rows = $this->model->where(['approved' => 'true'])->latest()->get();
        return view('admin.'.$this->route.'.index', compact('rows'));
    }
    public function not_active_users()
    {
        $rows = $this->model->where(['approved' => 'false'])->latest()->get();
        return view('admin.'.$this->route.'.index', compact('rows'));
    }
    public function edit($id)
    {
        $row = $this->model->find($id);
        return View('admin.' . $this->route . '.edit', compact('row'));
    }

    public function show($id)
    {
        $row = $this->model->find($id);
        $banks=Bank::where('user_id',$id)->get();
        $row->categories ?$categories=Category::whereIn('id',$row->categories)->get() :$categories=[] ;
        $samples=Sample::where('user_id',$id)->get();
        return View('admin.' . $this->route . '.show', compact('row', 'banks','categories','samples'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, $this->validation_func(2, $id),$this->validation_msg());
        $obj = $this->model->find($id);
        $obj->update($request->all());
        return redirect('admin')->with('updated', 'تم التعديل بنجاح');
    }
    public function create()
    {
        $categories=Category::whereStatus('active')->get();
        return view('admin.' . $this->route . '.create', compact('categories'));
    }
    public function store(Request $request)
    {
        $this->validate($request, $this->validation_func(1),$this->validation_msg());
        $user = $this->model->create([
            'username'=>$request['username'],
            'password'=>$request['password'],
            'mobile'=>$request['mobile'],
            'city_id'=>$request['city_id'],
            'status'=>'active',
            'approved'=>'true',
            'image'=>$request['image']
        ]);
        $user->refresh();

        return redirect('admin/active_user')->with('created', 'تمت الاضافة بنجاح');
    }

    public function validation_func($method,$id=null)
    {
        if($method == 1) // POST Case
            return ['username' => 'required', 'mobile' => 'required|unique:users', 'image' => 'mimes:png,jpg,jpeg','password'=>'required|min:6'];
        return ['username' => 'required', 'mobile' => 'required|unique:users,mobile,'.$id, 'image' => 'mimes:png,jpg,jpeg'];
    }
    public function validation_msg()
    {
        $messsages = array(
            'username.required' => 'يجب ملئ جميع الحقول',
            'city_id.required' => 'يجب ملئ جميع الحقول',
            'mobile.required' => 'يجب ملئ جميع الحقول',
            'password.required' => 'يجب ملئ جميع الحقول',
            'mobile.unique' => 'هذا الهاتف مسجل بالفعل',
        );
        return $messsages;
    }
    public function destroy($id) {
        $this->model->find($id)->delete();
        return redirect()->back()->with('deleted','تم الحذف بنجاح');
    }


}
