<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\MasterController;
use App\Models\Ad;
use App\Models\Admin;
use App\Models\Category;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\Language;
use App\Models\Product;
use App\Models\SubCategory;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Analytics;
use Auth;
class LanguageController extends MasterController
{
    public function __construct(Language $model)
    {
        $this->model = $model;
        $this->route = 'language';
        $this->module_name         = 'قائمة اللغات';
        $this->single_module_name  = 'لغة';
        $this->index_fields        = ['الاسم' => 'name','الاختصار الخاص' => 'label'];
        $this->create_fields        = ['الاسم' => 'name','الاختصار الخاص' => 'label'];
        $this->update_fields        = ['الاسم' => 'name','الاختصار الخاص' => 'label'];
        parent::__construct();
    }


    public function validation_func($method,$id=null)
    {
        if($method == 1) // POST Case
            return ['name' => 'required','label'=>'required'];
        return ['name' => 'required','label'=>'required'];
    }



}
