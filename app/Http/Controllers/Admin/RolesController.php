<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\Shop;
use Illuminate\Http\Request;

class RolesController extends MasterController
{
    public function __construct(Role $model)
    {
        $this->model = $model;
        $this->language = '1';
        $this->route = 'roles';
        $this->module_name         = 'الأدوار ';
        $this->single_module_name  = 'الأدوار';
        $this->index_fields        = ['إسم الدور' => 'name'];
     //   $this->json_fields        = ["الاسم" => "name","الوصف"=>"note"];
        parent::__construct();
    }

    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['name'] ='required';
        $therulesarray['permissions'] ='required';
        return $therulesarray;
    }
    public function destroy($id)
    {
        Role::whereId($id)->delete();
        return redirect('admin/' . $this->route . '')->with('deleted', 'تم الحذف بنجاح');
    }
    public function store(Request $request)
    {
        $this->validate($request, $this->validation_func(1));

        $obj = $this->model->create($request->all());
        foreach ($request->permissions as $value){
            $permission = new RolePermission();
            $permission->permission_id = $value;
            $permission->role_id =$obj->id;
            $permission->save();
        }
        return redirect('admin/' . $this->route . '')->with('created', 'تمت الاضافة بنجاح');
    }
    public function edit($id)
    {
        $row = $this->model->find($id);
        $permissions = Permission::all();
        $roles = Role::all();
        return View('admin.' . $this->route . '.edit', compact('row', 'roles','permissions'));
    }
    public function update($id, Request $request)
    {
        $this->validate($request, $this->validation_func(2, $id));
        $obj = $this->model->find($id);
        $obj->update($request->all());
        RolePermission::where('role_id',$obj->id)->delete();
        foreach ($request['permissions'] as $key => $value) {
           // $obj->attachPermission($value);
            $permission = new RolePermission();
            $permission->permission_id = $value;
            $permission->role_id =$obj->id;
            $permission->save();
        }
        return redirect('admin/' . $this->route . '')->with('updated', 'تم التعديل بنجاح');
    }
}
