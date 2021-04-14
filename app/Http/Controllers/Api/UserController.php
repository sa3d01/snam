<?php

namespace App\Http\Controllers\Api;

use App\Models\Ad;
use App\Models\Rating;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;


class UserController extends MasterController
{
    public function __construct(User $model)
    {
        $this->model = $model;
        $this->route = 'users';
        $this->apiToken = true;
        parent::__construct();
    }

    public function validation_rules($method, $id = null)
    {
        if ($method == 2) {
            $rules['mobile'] = 'unique:users,mobile,' . $id;
            $rules['email'] = 'email|max:255|unique:users,email,' . $id;
        } else {
            $rules = [
                'mobile' => 'unique:users',
                'email' => 'unique:users',
                'device_token' => 'required',
            ];
        }
        return $rules;
    }

    public function validation_messages($lang = 'ar')
    {
        $messsages = [];
        if ($lang == 'ar') {
            $messsages = array(
                'email.unique' => 'هذا البريد مسجل بالفعل',
                'mobile.unique' => 'هذا الهاتف مسجل بالفعل',
            );
        }
        return $messsages;
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), $this->validation_rules(1), $this->validation_messages());
        if ($validator->passes()) {
            $all = $request->all();

            $first_val = substr($request->mobile, 0, 1);
            if ($first_val == '0') {
                $mobile = '+966' . substr($request->mobile, 1);
            } elseif ($first_val == '5') {
                $mobile = '+966' . $request->mobile;
            } else {
                $mobile = $request->mobile;
            }
            $check_user = $this->model->where('mobile', $mobile)->first();
            if ($check_user) {
                return response()->json(['status' => 400, 'msg' => 'هذا الهاتف مسجل من قبل']);
            }
            try {
                $obj = $this->model->create($all);
            } catch (\Exception $e) {
                return response()->json(['status' => 400, 'msg' => $e->getMessage()]);
            }
            $activation_code = rand(1111, 9999);
            $this->update_apiToken($obj);
            //send_sms
            $msg = ' كود التفعيل الخاص بسنام' . $activation_code;
            $obj->sendMessage($msg,$obj->mobile);
            $obj->refresh();
            $arr = $obj;
            $arr['activation_code'] = $activation_code;
            return response()->json(['status' => 200, 'data' => $arr]);
        } else {
            return response()->json(['status' => 400, 'msg' => $validator->errors()->first()]);
        }
    }

    public function login(Request $request)
    {
        $validate = \Validator::make($request->all(), ['device_token' => 'required']);
        if ($validate->fails()) {
            return response()->json(['status' => 400, 'msg' => $validate->errors()->first()]);
        }
        $first_val = substr($request->mobile, 0, 1);
        if ($first_val == '0') {
            $mobile = '+966' . substr($request->mobile, 1);
        } else {
            $mobile = '+966' . $request->mobile;
        }
        $user = User::where('mobile', $mobile)->first();
        if (!$user) {
            return response()->json(['status' => 400, 'msg' => 'رقم الجوال غير صحيح']);
        }
        if (Auth::attempt(['mobile' => $mobile, 'password' => $request->input('password'), 'approved' => 'false'])) {
            $msg = 'تم حظرك من قبل ادارة التطبيق';
            return response()->json(['status' => 400, 'msg' => $msg]);
        }
        if (Auth::attempt(['mobile' => $mobile, 'password' => $request->input('password'), 'status' => 'active'])) {
            $user = Auth::user();
            $this->update_tokens($user, $request->device_token);
            $user->refresh();
            return response()->json(['status' => 200, 'data' => $user->static_model()]);
        } elseif (Auth::attempt(['mobile' => $mobile, 'password' => $request->input('password'), 'status' => 'not_active'])) {
            $user = Auth::user();
            $activation_code = rand(1111, 9999);
            $this->update_apiToken($user);
            //send_sms
            $msg = ' كود التفعيل الخاص بسنام' . $activation_code;
            $user->sendMessage($msg,$mobile);
            $user->refresh();
            $arr = $user;
            $arr['activation_code'] = $activation_code;
            return response()->json(['status' => 200, 'data' => $arr]);
        } else {
            $msg = 'كلمة المرور غير صحيحة';
            return response()->json(['status' => 400, 'msg' => $msg]);
        }
    }

    public function logout(Request $request)
    {
        return response()->json(['status' => 200, 'data' => '']);
    }

    public function show($id, Request $request)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['status' => 400, 'msg' => 'Invalid authentication token in request ']);
        }
        //auth
        $arr = $user->static_model();
        $count_rating = Rating::where('rated_id', $user->id)->count();
        $sum_rating = Rating::where('rated_id', $user->id)->sum('rate');
        $arr['total_rating'] = $count_rating == 0 ? 0 : round($sum_rating / $count_rating);
        $comments = [];
        $ratings = Rating::where('rated_id', $user->id)->get();
        foreach ($ratings as $rating) {
            $u = User::where('id', $rating->rating_id)->first();
            $r_arr['id'] = $rating->id;
            $r_arr['rating'] = $u->static_model();
            $r_arr['rate'] = $rating->rate;
            $r_arr['comment'] = $rating->comment ? $rating->comment : '';
            $r_arr['created_at'] = $rating->published();
            $comments[] = $r_arr;
        }
        $arr['comments'] = $comments;
        $ads = [];
        foreach ($user->ads as $ad) {
            $d_arr['id'] = $ad->id;
            $d_arr['image'] = asset('images/ads/') . '/' . $ad->images[0];
            $d_arr['title'] = $ad->title;
            $d_arr['city'] = $ad->city->name;
            $d_arr['created_at'] = $ad->published();
            $ads[] = $d_arr;
        }
        $arr['ads'] = $ads;
        return response()->json(['status' => 200, 'data' => $arr]);
    }

    public function user_ads($id, Request $request)
    {
        $offset = $request->header('skip', 0);
        $rows = Ad::where('user_id', $id)->latest()->offset($offset)->limit(10)->get();
        $count = Ad::where('user_id', $id)->latest()->count();
        $data = [];
        foreach ($rows as $row) {
            $arr = $row->static_model();
            $arr['is_favourite'] = false;
            unset($arr['user']);
            if ($request->header('apiToken')) {
                $check_token = $this->check_apiToken($request->header('apiToken'));
                if ($check_token && $this->apiToken == true) {
                    return $check_token;
                }
                $split = explode("sa3d01", $request->header('apiToken'));
                $user = User::where('apiToken', $split['1'])->first();
                $fav = Favourite::where(['ad_id' => $row->id, 'user_id' => $user->id])->first();
                $arr['is_favourite'] = $fav ? true : false;
            }
            $data[] = $arr;
        }
        return response()->json(['status' => 200, 'data_count' => $count, 'data' => $data]);
    }

    public function reset_password(Request $request)
    {
        try {
            $validate = \Validator::make($request->all(), ['mobile' => 'required']);
            if ($validate->fails()) {
                return response()->json(['status' => 400, 'msg' => 'رقم الجوال مطلوب']);
            }
            $first_val = substr($request->mobile, 0, 1);
            if ($first_val == '0') {
                $mobile = '+966' . substr($request->mobile, 1);
            } else {
                $mobile = '+966' . $request->mobile;
            }
            $user = User::where('mobile', $mobile)->first();
            if ($user) {
                $user->update(['password' => $request['password'], 'apiToken' => Str::random(60)]);
                $user->refresh();
                return response()->json(['status' => 200, 'data' => $user->static_model(), 'apiToken' => $user->apiToken]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'هذا الهاتف غير مسجل من قبل']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 400, 'msg' => $e->getMessage()]);
        }
    }

    public function resend_code(Request $request)
    {
        $validate = \Validator::make($request->all(), ['mobile' => 'required']);
        if ($validate->fails()) {
            return response()->json(['status' => 400, 'msg' => 'رقم الجوال مطلوب']);
        }
        $first_val = substr($request->mobile, 0, 1);
        if ($first_val == '0') {
            $mobile = '+966' . substr($request->mobile, 1);
        } else {
            $mobile = '+966' . $request->mobile;
        }
        $user = User::where('mobile', $mobile)->first();
        if ($user) {
            $code = rand(1111, 9999);
            $msg = ' كود التفعيل الخاص بسنام' . $code;
            $user->sendMessage($msg,$user->mobile);
            $arr = $user;
            $arr['activation_code'] = $code;
            return response()->json(['status' => 200, 'data' => $arr]);
        } else {
            return response()->json(['status' => 400, 'msg' => 'هذا الهاتف غير مسجل من قبل']);
        }
    }

    public function resetPassword(Request $request)
    {
        $validate = \Validator::make($request->all(), ['mobile' => 'required', 'password' => 'required']);
        if ($validate->fails()) {
            return response()->json(['status' => 400, 'msg' => 'رقم الجوال وكلمة المرور مطلوبان']);
        }
        $first_val = substr($request->mobile, 0, 1);
        if ($first_val == '0') {
            $mobile = '+966' . substr($request->mobile, 1);
        } else {
            $mobile = '+966' . $request->mobile;
        }
        $user = User::where('mobile', $mobile)->first();
        if ($user) {
            $user->update(['password' => $request['password'], 'apiToken' => Str::random(60)]);
            $user->refresh();
            return response()->json(['status' => 200, 'data' => $user->static_model(), 'apiToken' => $user->apiToken]);
        } else {
            return response()->json(['status' => 400, 'msg' => 'هذا الهاتف غير مسجل من قبل']);
        }
    }

    public function update_password(Request $request)
    {
        //auth
        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        } elseif (!$check_token) {
            $split = explode("sa3d01", $request->header('apiToken'));
            $user = User::where('apiToken', $split['1'])->first();
        }
        //auth
        $validate = \Validator::make($request->all(), ['new_password' => 'required']);
        if ($validate->fails()) {
            return response()->json(['status' => 400, 'msg' => 'يجب ادخال كل من كلمة المرور']);
        }
        if ($request->old_password) {
            if (Auth::attempt(['mobile' => $user->mobile, 'password' => $request->input('old_password')])) {
                $user = Auth::user();
                $user->update(['password' => $request['new_password']]);
                $user->refresh();
                return response()->json(['status' => 200, 'data' => $user->static_model()]);
            } else {
                return response()->json(['status' => 400, 'msg' => 'يوجد مشكلة بالبيانات المدخلة']);
            }
        } else {
            $user->update(['password' => $request['new_password']]);
            $user->refresh();
            return response()->json(['status' => 200, 'data' => $user->static_model()]);
        }
    }

    public function active(Request $request)
    {
        //auth
        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        }
        $split = explode("sa3d01", $request->header('apiToken'));
        $user = User::where('apiToken', $split['1'])->first();
        $user->status = 'active';
        $user->update();
        $user->refresh();
        return response()->json(['status' => 200, 'data' => $user->static_model()]);
    }

    public function update($id, Request $request)
    {
        $check_token = $this->check_apiToken($request->header('apiToken'));
        if ($check_token) {
            return $check_token;
        }
        $split = explode("sa3d01", $request->header('apiToken'));
        $user = User::where('apiToken', $split['1'])->first();
        //auth
        $validator = \Validator::make($request->all(), $this->validation_rules(2, $user->id), $this->validation_messages());
        if ($validator->passes()) {
            $row = $this->model->find($user->id);
            if (!$row) {
                return response()->json(['status' => 400]);
            }
            $first_val = substr($request->mobile, 0, 1);
            if ($first_val == '0') {
                $mobile = '+966' . substr($request->mobile, 1);
            } else {
                $mobile = '+966' . $request->mobile;
            }
            $all = $request->all();
            if ($request['delete'] == 'image') {
                $all['image'] = 'default.jpeg';
            }
            if ($request->mobile && $row->mobile != $mobile) {
                $all['status'] = 'not_active';
                $row->update($all);
                $activation_code = rand(1111, 9999);
                $this->update_apiToken($row);
                $msg = ' كود التفعيل الخاص بسنام' . $activation_code;
                $row->sendMessage($msg,$row->mobile);
                $row->refresh();
                $arr = $row;
                $arr['activation_code'] = $activation_code;
                return response()->json(['status' => 200, 'data' => $arr]);
            } else {
                $row->update($all);
                return response()->json(['status' => 200, 'data' => $row->static_model()]);
            }
        } else {
            return response()->json(['status' => 400, 'msg' => $validator->errors()->first()]);
        }
    }

}
