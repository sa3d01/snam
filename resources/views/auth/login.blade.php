@extends('web.layouts.app')
@section('content')
    <div class="auth-body container">
        <div class="row">
            <!-- login -->
            <div class="col-xl-6 col-lg-12 text-white py-5 login">
                @if(count($errors)>0)
                    @foreach($errors->all() as $error)
                        <div class="alert alert-info">
                            <p class="text-center">
                                {{$error}}
                            </p>
                        </div>
                    @endforeach
                @endif
                <h3 style="color: #0b0b0b" class="text-center pb-4">تسجيل الدخول</h3>
                {!! Form::open(['method'=>'post','files'=>true, 'enctype' => 'multipart/form-data', 'route'=>['web.login.submit']]) !!}
                    <div class="form-group">
                        <input type="text" name="mobile"
                               class="form-control br-50"
                               placeholder="رقم الجوال">
                    </div>
                    <div class="form-group">
                        <input type="password" name="password"
                               class="form-control br-50"
                               placeholder="كلمة المرور" id="login-password">
                    </div>
                    <div class="form-group text-center w-100">
                        <button type="submit" class="btn default-bg text-white br-50 px-5 mt-3">تسجيل
                            دخول</button>
                    </div>
                {!! Form::close() !!}
            </div>
            <!-- sign up -->
            <div class="col-xl-6 col-lg-12 text-white py-5 signup">
                @if(count($errors)>0)
                    @foreach($errors->all() as $error)
                        <div class="alert alert-info">
                            <p class="text-center">
                                {{$error}}
                            </p>
                        </div>
                    @endforeach
                @endif
                <h3 style="color: #0b0b0b" class="text-center pb-4">تسجيل جديد</h3>
                {!! Form::open(['method'=>'post','files'=>true, 'enctype' => 'multipart/form-data', 'route'=>['register']]) !!}
                <div class="form-group">
                    <input type="text" name="username"
                           class="form-control br-50"
                           placeholder="اسم المستخدم">
                </div>
                <div class="form-group">
                    <input type="text" name="mobile"
                           class="form-control br-50"
                           placeholder="رقم الجوال">
                </div>
                <div class="form-group">
                    @php($cities=\App\Models\City::all())
                    <select name="city_id"
                            class="form-control br-50"
                            id="select-city">
                        <option value="" disabled selected hidden>المدينة</option>
                        @foreach($cities as $city)
                            <option value="{{$city->id}}">{{$city->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <input type="password" name="password"
                           class="form-control br-50"
                           placeholder="كلمة المرور" id="signup-password">
                </div>
                <div class="form-group text-center w-100">
                    <button type="submit" class="btn default-bg text-white br-50 px-5 mt-3">تسجيل
                        </button>
                </div>
                    <h6 style="color: #0b0b0b" class="forget-pass py-3 f-13">بالتسجيل انت توافق على
                        <a href="{{route('licence')}}">
                            <u class="text-dark">الشروطوالأحكام </u>
                        </a>
                    </h6>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
