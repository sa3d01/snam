<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;

class OrderController extends MasterController
{

    public function __construct(Order $model)
    {
        $this->model = $model;
        $this->route = 'orders';
        $this->apiToken = true;
        parent::__construct();
    }
    public function validation_rules($method,$id=null)
    {
        $rules = [
            'client_lat'=>'required',
            'client_long'=>'required',
            'note'=>'required',
        ];
        return $rules;
    }
    public function validation_messages($lang)
    {
        $messsages=[];
        if ($lang=='ar'){
            $messsages = array(
                'note.required'=>'يرجى ادخال تفاصيل الطلب',
            );
        }
        return $messsages;
    }
}