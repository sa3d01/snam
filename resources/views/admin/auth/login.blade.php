<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">
    <link rel="shortcut icon" href="{{asset('panel/assets/images/favicon_1.ico')}}">
    <title>لوحة التحكم</title>
    <link href="{{asset('panel/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/components.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('panel/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('admin')}}" rel="stylesheet" type="text/css" />
    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <script src="{{asset('panel/assets/js/modernizr.min.js')}}"></script>
</head>
<body>
@include('admin.layouts.alerts')
<div class="account-pages"></div>
<div class="clearfix"></div>
<div class="wrapper-page">
    <div class=" card-box">
        <div class="panel-heading">
            <h3 class="text-center"> الدخول إلى <strong class="text-custom">لوحة التحكم</strong> </h3>
        </div>
        <div class="panel-body">
            <form class="form-horizontal m-t-20" method="POST" action="{{ route('admin.login.submit') }}">
                {{ csrf_field() }}
                <div class="form-group ">
                    <div class="col-xs-12">
                        <input name="email" class="form-control" type="email" required="" placeholder="البريد الإلكترونى">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <input name="password" class="form-control" type="password" required="" placeholder="كلمة المرور">
                    </div>
                </div>
                <div class="form-group ">
                    <div class="col-xs-12">
                        <div class="checkbox checkbox-primary">
                            <input name="remember" id="checkbox-signup" type="checkbox">
                            <label for="checkbox-signup">
                                تذكرنى
                            </label>
                        </div>
                    </div>
                </div>
                <div class="form-group text-center m-t-40">
                    <div class="col-xs-12">
                        <button class="btn btn-pink btn-block text-uppercase waves-effect waves-light" type="submit">دخول</button>
                    </div>
                </div>
                <div class="form-group m-t-30 m-b-0">
                    <div class="col-sm-12">
                        <a href="{{route('admin.password.request')}}" class="text-dark"><i class="fa fa-lock m-r-5"></i>نسيت كلمت المرور ؟</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var resizefunc = [];
</script>
<!-- jQuery  -->
<script src="{{asset('panel/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('panel/assets/js/bootstrap-rtl.min.js')}}"></script>
<script src="{{asset('panel/assets/js/detect.js')}}"></script>
<script src="{{asset('panel/assets/js/fastclick.js')}}"></script>
<script src="{{asset('panel/assets/js/jquery.slimscroll.js')}}"></script>
<script src="{{asset('panel/assets/js/jquery.slimscroll.js')}}"></script>
<script src="{{asset('panel/assets/js/waves.js')}}"></script>
<script src="{{asset('panel/assets/js/wow.min.js')}}"></script>
<script src="{{asset('panel/assets/js/jquery.nicescroll.js')}}"></script>
<script src="{{asset('panel/assets/js/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('panel/assets/js/jquery.core.js')}}"></script>
<script src="{{asset('panel/assets/js/jquery.app.js')}}"></script>
<script src="{{asset('panel/assets/plugins/notifyjs/js/notify.js')}}"></script>
<script src="{{asset('panel/assets/plugins/notifications/notify-metro.js')}}"></script>
@if($errors->any())
    <div style="visibility: hidden" id="errors" data-content="{{$errors->first()}}"></div>
    <script type="text/javascript">
        $(document).ready(function () {
            var errors=$('#errors').attr('data-content');
            $.Notification.notify('error','top left', 'عذرا ..', errors);
        })
    </script>
@endif
</body>
</html>