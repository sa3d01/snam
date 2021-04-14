<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Route;

class WebLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:web', ['except' => ['logout']]);
        $cities=City::where('status','active')->get();
        view()->share(array(
            'cities'=>$cities,
        ));
    }

    public function web_login(Request $request)
    {
        $this->validate($request, [
            'mobile'   => 'required',
            'password' => 'required|min:6'
        ]);
        $first_val = substr($request->mobile, 0, 1 );
        if($first_val=='0'){
            $mobile = '+966'.substr($request->mobile, 1);
        }else{
            $mobile = '+966'.$request->mobile;
        }
        if (Auth::guard('web')->attempt(['mobile' => $mobile, 'password' => $request->password], $request->remember)) {
            return redirect()->intended(url('/'));
        }
        return redirect()->back()->withErrors(['يوجد مشاكل بالبيانات المدخلة']);
    }

    public function client_login(Request $request)
    {
        $this->validate($request, [
            'username'   => 'required',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('client')->attempt(['username' => $request->username, 'password' => $request->password,'status'=>'active'], $request->remember)) {
            return redirect()->intended(url('/'));
        }
        return redirect()->back()->withErrors(['يوجد مشاكل بالبيانات المدخلة']);
    }

    public function union_login(Request $request)
    {
        $this->validate($request, [
            'username'   => 'required',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password,'status'=>'active','type'=>'union'], $request->remember)) {
            return redirect()->intended(url('/'));
        }
        return redirect()->back()->withErrors(['يوجد مشاكل بالبيانات المدخلة']);
    }

    public function web_logout()
    {
        Auth::user()->logout();
        return redirect('/');
    }
    public function client_logout()
    {
        Auth::guard('client')->logout();
        return redirect('/');
    }
}