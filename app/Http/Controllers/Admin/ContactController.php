<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;

use App\Models\Contact;
use App\User;
use Illuminate\Http\Request;

class ContactController extends MasterController
{
    public function __construct(Contact $model)
    {
        $this->model = $model;
        $this->route = 'contact';
        $this->module_name         = 'قائمة رسائل ';
        $this->single_module_name  = 'رسالة';
        $this->index_fields        = ['اسم المرسل'=>'name','بريد المرسل'=>'email','الرسالة'=>'message','تاريخ الإرسال'=>'created_at','حالة الرسالة'=>'read'];
        $this->show_fields        = ['اسم المرسل'=>'name','بريد المرسل'=>'email','الرسالة'=>'message','تاريخ الإرسال'=>'created_at','حالة الرسالة'=>'read'];
        parent::__construct();
    }
    public function show($id)
    {
        $row = $this->model->find($id);
        $row->read='true';
        $row->update();
        return View('admin.'.$this->route.'.show', compact('row'));
    }
}
