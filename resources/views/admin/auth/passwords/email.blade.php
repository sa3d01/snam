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
			<div class=" card-box">
				<div class="panel-heading">
					<h3 class="text-center"> إستعادة كلمة المرور </h3>
				</div>

				<div class="panel-body">

					<form method="post" action="{{ route('admin.password.email') }}" role="form" class="text-center">
						{{ csrf_field() }}
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">
								×
							</button>
							قم بادخال <b>بريدك الالكترونى</b> ليتم ارسال رابط التفعيل اليك!
						</div>
						<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} m-b-0">
							<div class="input-group">
								<input name="email" type="email" class="form-control" value="{{ old('email') }}" required="">
								<span class="input-group-btn">
									<button type="submit" class="btn btn-pink w-sm waves-effect waves-light">
										إرسال
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
                    $.Notification.notify('error','top left', 'عذرا ..', 'هذا البريد غير مسجل من قبل');
                })
			</script>
		@endif
		@if (session('status'))
			<script type="text/javascript">
                $(document).ready(function () {
                    $.Notification.notify('success','top left', 'تم الارسال ..', 'تفقد بريدك الإلكترونى');
                })
			</script>
		@endif

	</body>
</html>