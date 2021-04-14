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
                <h4>تسجيل الدخول</h4>
                <span>دخول لحساب مسجل بسوق تبوك</span>
                {!! Form::open(['method'=>'post','files'=>true, 'enctype' => 'multipart/form-data','class'=>'text-white py-5', 'route'=>['web.login.submit']]) !!}
                    <div class="form-group">
                        <img src="{{asset('images/icons/auth_phone.png')}}" class="login-icon">
                        <input type="text" name="mobile"
                               class="form-control text-white bg-none white-placeholder search-focus border-0"
                               placeholder="رقم الجوال">
                    </div>
                    <div class="form-group">
                        <img src="{{asset('images/icons/auth_password.png')}}" class="login-icon">
                        <input type="password" name="password"
                               class="form-control text-white bg-none white-placeholder search-focus border-0"
                               placeholder="كلمة المرور" id="login-password">
                        <i class="far fa-eye show-pass" onclick="show()"></i>
                    </div>

                    <button type="submit" class="btn login-btn my-4">دخول</button>
                    <h6 class="forget-pass py-3"> نسيت كلمة المرور؟</h6>
                </form>
                <hr class="d-lg-none" />

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
                <h4>حساب جديد</h4>
                <span>تسجيل حساب جديد بسوق تبوك</span>
                {!! Form::open(['method'=>'post','files'=>true, 'enctype' => 'multipart/form-data','class'=>'text-white py-5', 'route'=>['register']]) !!}
                    <div class="form-group">
                        <img src="{{asset('images/icons/auth_user_name.png')}}" class="signup-icon">
                        <input type="text" name="username"
                               class="form-control text-white bg-none white-placeholder search-focus border-0"
                               placeholder="اسم المستخدم">
                    </div>
                    <div class="form-group">
                        <img src="{{asset('images/icons/auth_phone.png')}}" class="signup-icon">
                        <input type="text" name="mobile"
                               class="form-control text-white bg-none white-placeholder search-focus border-0"
                               placeholder="رقم الجوال">
                    </div>
                    <div class="form-group">
                        <img src="{{asset('images/icons/filter_city.png')}}" class="signup-icon">

                        <select name="city_id"
                                class="form-control  bg-none  search-focus border-0 white-placeholder choose-city-placeholder"
                                id="select-city">
                            <option value="" disabled selected hidden class="text-white">المدينة</option>
                            @foreach($cities as $city)
                                <option value="{{$city->id}}">{{$city->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <img src="{{asset('images/icons/auth_password.png')}}" class="signup-icon">
                        <input type="password" name="password"
                               class="form-control text-white bg-none white-placeholder search-focus border-0"
                               placeholder="كلمة المرور" id="signup-password">
                        <i class="far fa-eye show-pass" onclick="showSignup()"></i>
                    </div>

                    <button type="submit" class="btn signup-btn my-4">تسجيل</button>

                    <h6 class="forget-pass py-3">بالتسجيل انت توافق على
                        <a href="{{route('licence')}}">
                            <u class="text-white">الشروطوالأحكام </u>
                        </a>
                    </h6>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
