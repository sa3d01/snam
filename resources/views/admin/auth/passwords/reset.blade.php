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
        <link href="{{asset('panel/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

        <script src="{{asset('panel/assets/js/modernizr.min.js')}}"></script>

	</head>
	<body>

		<div class="account-pages"></div>
		<div class="clearfix"></div>
		<div class="wrapper-page">
			<div class=" card-box" >
				<div class="panel-heading">
					<h3 class="text-center"> إستعادة كلمة المرور </h3>
				</div>

				<div class="panel-body">

					<form method="post" action="{{ route('admin.password.request') }}" role="form" class="text-center">
						{{ csrf_field() }}
						<input type="hidden" name="token" value="{{ $token }}">
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
							<label for="email" class="col-md-3 control-label">البريد الالكترونى</label>
							<div class="col-md-9 input-group">
								<input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}" required autofocus>
							</div>
						</div>
						<div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
							<label for="password" class="col-md-3 control-label">كلمة المرور</label>
							<div class="col-md-9 input-group">
								<input id="password" type="password" class="form-control" name="password" required>
							</div>
						</div>
						<div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
							<label for="password-confirm" class="col-md-3 control-label">تأكيد كلمة المرور</label>
							<div class="col-md-9 input-group">
								<input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
							</div>
						</div>



						<div class="form-group">
							<div class="input-group">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-pink w-sm waves-effect waves-light">
										اتمام
									</button> 
								</span>
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
        <script src="{{asset('panel/assets/js/jquery.blockUI.js')}}"></script>
        <script src="{{asset('panel/assets/js/waves.js')}}"></script>
        <script src="{{asset('panel/assets/js/wow.min.js')}}"></script>
        <script src="{{asset('panel/assets/js/jquery.nicescroll.js')}}"></script>
        <script src="{{asset('panel/assets/js/jquery.scrollTo.min.js')}}"></script>


        <script src="{{asset('panel/assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('panel/assets/js/jquery.app.js')}}"></script>
		<script src="{{asset('panel/assets/plugins/notifyjs/js/notify.js')}}"></script>
		<script src="{{asset('panel/assets/plugins/notifications/notify-metro.js')}}"></script>
		@if($errors->has('email'))
			<strong style="visibility: hidden" id="error" data-content="{{$errors->first('email')}}"></strong>
			<script type="text/javascript">
                $(document).ready(function () {
                    var error=$("#error").attr('data-content');
                    $.Notification.notify('error','top left', 'عذرا ..', error);
                })
			</script>
		@endif
		@if($errors->has('password'))
			<strong style="visibility: hidden" id="error" data-content="{{$errors->first('password')}}"></strong>
			<script type="text/javascript">
                $(document).ready(function () {
                    var error=$("#error").attr('data-content');
                    $.Notification.notify('error','top left', 'عذرا ..', error);
                })
			</script>
		@endif
		@if($errors->has('password_confirmation'))
			<strong style="visibility: hidden" id="error" data-content="{{$errors->first('password_confirmation')}}"></strong>
			<script type="text/javascript">
                $(document).ready(function () {
                    var error=$("#error").attr('data-content');
                    $.Notification.notify('error','top left', 'عذرا ..', error);
                })
			</script>
		@endif

	</body>
</html>