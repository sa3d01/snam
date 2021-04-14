<?php
namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Bank;
use App\Models\Category;
use App\Models\City;
use App\Models\Client;
use App\Models\Contact;
use App\Models\Language;
use App\Models\Order;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Setting;
use App\Models\Shop;
use App\User;
use Illuminate\Http\Request;
use Auth;

abstract class MasterController extends Controller {

    protected $model;         // model name
    protected $language;         // number of languages
    protected $route;         // route name
    protected $perPage = 10;  // pagination
    protected $validation_c;  // validation on create
    protected $validation_u;  // validation on update
    protected $module_name;
    protected $single_module_name;
    protected $index_fields;
    protected $show_fields;
    protected $create_fields;
    protected $update_fields;
    protected $json_fields;

    public function __construct()
    {
        $categories=Category::where(['status'=>'active'])->get();
        $category_array=[];
        foreach ( $categories as $category) {
            $category_array[$category->id] = $category->name;
        }
        $districts=City::where(['type'=>'district'])->get();
        $district_array=[];
        foreach ( $districts as $district) {
            $district_array[$district->id] = $district->name;
        }
        $parent_categories=Category::where(['parent_id'=>null])->get();
        $parent_category_array=[];
        $parent_category_array[null] = 'بدون';
        foreach ( $parent_categories as $category) {
            $parent_category_array[$category->id] = $category->name;
        }
        $cities=City::all();
        $city_array=[];
        foreach ( $cities as $city) {
            $city_array[$city->id] = $city->name;
        }
        $live_times_array=[];
        $live_times_array['15']='15';
        $live_times_array['30']='30';
        $live_times_array['45']='45';
        $user_array = [];
        $users=User::where(['status'=>'active'])->get();
        foreach ( $users as $user) {
            $user_array[$user->id] = $user->username;
        }
        $this->middleware('auth:admin');
        view()->share(array(
            'module_name'        => $this->module_name,
            'single_module_name' => $this->single_module_name,
            'route'              => $this->route,
            'language'              => $this->language,
            'index_fields'       => $this->index_fields,
            'create_fields'      => $this->create_fields,
            'update_fields'      => $this->update_fields,
            'show_fields'      => $this->show_fields,
            'category'        => $category_array,
            'district'        => $district_array,
            'parent_category'        => $parent_category_array,
            'user'        => $user_array,
            'city'        => $city_array,
            'live_time'        => $live_times_array,
            'status'        => ['active'=>'مفعل','not_active'=>'غير مفعل'],
            'setting'        => Setting::first(),
            'admin_count'=>Admin::count(),
            'active_user_count'=>User::where('approved','true')->count(),
            'not_active_user_count'=>User::where('approved','false')->count(),
            'bank_count'=>Bank::count(),
            'read_contacts_count' => Contact::where('read','true')->count(),
            'non_read_contacts_count' => Contact::where('read','false')->count(),
            'city_count' => City::count(),
            'active_ad_count' => Ad::where('status','active')->count(),
            'not_active_ad_count' => Ad::where('status','not_active')->count(),
        ));
    }

    public function index()
    {
        $rows = $this->model->latest()->get();
        return view('admin.' . $this->route . '.index', compact('rows'));
    }

    public function create()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        return view('admin.' . $this->route . '.create', compact('roles','permissions'));
    }

    public function store(Request $request)
    {
        $labels = Language::pluck('label')->toArray();
        $this->validate($request, $this->validation_func(1));
        $obj = $this->model->create($request->all());
        if ($request->role) {
            $role = new AdminRole();
            $role->admin_id = $obj->id;
            $role->role_id = $request->role;
            $role->save();
        }
        if ($request->permission){
            foreach ($request->permission as $value){
                $permission = new RolePermission();
                $permission->permission_id = $value;
                $permission->role_id =$obj->id;
                $permission->save();
            }
        }
        if ($this->language == 2) {
            foreach ($request->all() as $key => $value) {
                //مش كاى على طول علشان لو عندى اسم ووصف مثﻻ
                foreach ($labels as $label) {
                    $lang_label = substr($key, "-3");
                    if ($lang_label == '_' . $label) {
                        $in[$label] = $value;
                        $attr = substr_replace($key, "", -3);
                        $obj->$attr = $in;
                        $obj->update();
                    }
                }
            }
        }

        if ($request->images) {
            $i = 0;
            foreach ($request->images as $image) {
                $file = $image;
                $destinationPath = 'images/' . $this->route . '/';
                $filename = $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $img[$i] = $filename;
                $i++;
            }
            $obj->images = $img;
            $obj->update();
        }
        if ($this->route == 'admin'){
            return redirect('admin/admins')->with('created', 'تمت الاضافة بنجاح');

        }else{
            return redirect('admin/' . $this->route . '')->with('created', 'تمت الاضافة بنجاح');

        }
    }

    public function edit($id)
    {
        $row = $this->model->find($id);
        $roles = Role::all();
        return View('admin.' . $this->route . '.edit', compact('row', 'roles'));
    }

    public function update($id, Request $request)
    {
        $this->validate($request, $this->validation_func(2, $id));
        $obj = $this->model->find($id);
        $obj->update($request->all());

        if ($request->role) {
            AdminRole::where('admin_id', $id)->update(['role_id' => $request->role]);
        }
        if ($request->permission){
//            DB::table('permission_role')->where('role_id', $role->id)->delete();
            RolePermission::where('role_id',$obj->id)->delete();
            foreach ($request['permission'] as $key => $value) {
                $obj->attachPermission($value);
            }
        }else{
            RolePermission::where('role_id',$obj->id)->delete();

        }

        if ($this->language == 2) {
            $labels = Language::pluck('label')->toArray();
            foreach ($request->all() as $key => $value) {
                //مش كاى على طول علشان لو عندى اسم ووصف مثﻻ
                foreach ($labels as $label) {
                    $lang_label = substr($key, "-3");
                    if ($lang_label == '_' . $label) {
                        $in[$label] = $value;
                        $attr = substr_replace($key, "", -3);
                        $obj->$attr = $in;
                        $obj->update();
                    }
                }
            }
        }
        if ($request->images) {
            $i = 0;
            foreach ($request->images as $image) {
                $file = $image;
                $destinationPath = 'images/' . $this->route . '/';
                $filename = $file->getClientOriginalName();
                $file->move($destinationPath, $filename);
                $img[$i] = $filename;
                $i++;
            }
            $obj->images = $img;
            $obj->update();
        }
        return redirect('admin/' . $this->route . '')->with('updated', 'تم التعديل بنجاح');
    }

    public function destroy($id)
    {
        $this->model->find($id)->delete();
        return redirect('admin/' . $this->route . '')->with('deleted', 'تم الحذف بنجاح');
    }

    public function show($id)
    {
        $row = $this->model->find($id);
        return View('admin.' . $this->route . '.show', compact('row'));
    }

    public function Activate($id)
    {
        $row = $this->model->find($id);
        if ($row->status == 'not_active') {
            $this->model->find($id)->update(['status' => 'active']);
            return back()->with('success', 'تم الغاء التفعيل بنجاح');
        } else {
            $this->model->find($id)->update(['status' => 'not_active']);
            return back()->with('success', 'تم التفعيل بنجاح');
        }
    }

}

