<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bank;
use App\Models\BankDescription;
use App\Models\Language;
use App\Models\Shop;
use App\Models\ShopDescription;
use App\Models\ShopImage;
use Illuminate\Http\Request;

class BankController extends MasterController
{
    public function __construct(Bank $model)
    {
        $this->model = $model;
        $this->route = 'bank';
        $this->module_name         = 'قائمة الحسابات البنكية ';
        $this->single_module_name  = 'بنك';
        $this->index_fields        = ['اسم البنك' => 'name','رقم الحساب' => 'account_number','اسم المؤسسة' => 'company_name','شعار البنك' => 'logo','رقم الايبان' => 'iban'];
        $this->create_fields        = ['اسم البنك' => 'name','رقم الحساب' => 'account_number','اسم المؤسسة' => 'company_name','شعار البنك' => 'logo','رقم الايبان' => 'iban'];
        $this->update_fields        = ['اسم البنك' => 'name','رقم الحساب' => 'account_number','اسم المؤسسة' => 'company_name','شعار البنك' => 'logo','رقم الايبان' => 'iban'];
        parent::__construct();
    }
    public function store(Request $request) {
        $this->validate($request, $this->validation_func(1));
        $this->model->create($request->all());
        return redirect('admin/'.$this->route.'')->with('created', 'تمت الاضافة بنجاح');
    }
    public function update($id, Request $request) {
        $this->validate($request, $this->validation_func(2,$id));
        $obj=$this->model->find($id);
        $obj->update($request->all());
        return redirect('admin/'.$this->route.'')->with('updated','تم التعديل بنجاح');
    }
    public function validation_func($method,$id=null)
    {
        $therulesarray = [];
        $therulesarray['account_number'] ='required';
        $therulesarray['iban'] ='required';
        $therulesarray['company_name'] ='required';
        $therulesarray['name'] ='required';
        $therulesarray['logo'] ='required';
        return $therulesarray;
    }
}
