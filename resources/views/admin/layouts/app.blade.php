<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
        <meta name="author" content="Coderthemes">
        <link rel="shortcut icon" href="{{$setting->logo}}">
        <!--Morris Chart CSS -->

        <link href="{{url('panel/assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css')}}" rel="stylesheet" />
        <link href="{{url('panel/assets/plugins/switchery/css/switchery.min.css')}}" rel="stylesheet" />
        <link href="{{url('panel/assets/plugins/multiselect/css/multi-select.css')}}"  rel="stylesheet" type="text/css" />
        <link href="{{url('panel/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{url('panel/assets/plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" />
        <link href="{{url('panel/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />

		<link rel="stylesheet" href="{{asset('panel/assets/plugins/morris/morris.css')}}">
        <link href="{{asset('panel/assets/plugins/bootstrap-sweetalert/sweet-alert.css')}}" rel="stylesheet" type="text/css">
        <link href="{{asset('panel/assets/css/bootstrap-rtl.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('panel/assets/css/core.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('panel/assets/css/components.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('panel/assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('panel/assets/css/pages.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('panel/assets/css/responsive.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('panel/assets/plugins/datatables/jquery.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('panel/assets/plugins/datatables/buttons.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('panel/assets/plugins/datatables/fixedHeader.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('panel/assets/plugins/datatables/responsive.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('panel/assets/plugins/datatables/scroller.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('panel/assets/plugins/datatables/dataTables.colVis.css')}}" rel="stylesheet" type="text/css"/>
        <link href="{{asset('panel/assets/plugins/datatables/dataTables.bootstrap.min.css')}}" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="{{asset('panel/assets/dropify/dist/css/dropify.min.css')}}">
        <link href="{{asset('panel/assets/plugins/datatables/fixedColumns.dataTables.min.css')}}" rel="stylesheet" type="text/css"/>

        <link rel="stylesheet" href="{{ asset('panel/assets/plugins/fileinput/css/fileinput.css') }}">

        <link rel="stylesheet" media="screen" href="https://fontlibrary.org/face/droid-arabic-kufi" type="text/css"/>
        <style>
            p, a, h1, h2, h3, h4, td, table, div, span, li {
                font-family: 'DroidArabicKufiRegular' !important;
                font-weight: normal !important;
                font-style: normal !important;
            }
        </style>
        <title>@yield('title','لوحة التحكم')</title>
        @yield('style')
        <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <script src="{{asset('panel/assets/js/modernizr.min.js')}}"></script>
    </head>
    <body class="fixed-left">
        <!-- Begin page -->
        <div id="wrapper">
            <!-- Top Bar Start -->
            @include('admin.layouts.topbar')
            <!-- Top Bar End -->
            @include('admin.layouts.sidebar')
            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->                      
            <div class="content-page">
                <!-- Start content -->
                @yield('content')
                @include('admin.layouts.footer')
            </div>
            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->
        </div>
        <!-- END wrapper -->
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
        <!-- jQuery  -->
        <script src="{{asset('panel/assets/plugins/moment/moment.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/morris/morris.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/raphael/raphael-min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/bootstrap-sweetalert/sweet-alert.min.js')}}"></script>
        <!-- Todojs  -->
        <script src="{{asset('panel/assets/pages/jquery.todo.js')}}"></script>
        <!-- chatjs  -->
        <script src="{{asset('panel/assets/pages/jquery.chat.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/peity/jquery.peity.min.js')}}"></script>

		<script src="{{asset('panel/assets/pages/jquery.dashboard_2.js')}}"></script>
        <script src="{{asset('panel/assets/pages/jquery.sweet-alert.init.js')}}"></script>

        <script src="{{asset('panel/assets/plugins/datatables/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.bootstrap.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/buttons.bootstrap.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/jszip.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/pdfmake.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/vfs_fonts.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/buttons.html5.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/buttons.print.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.fixedHeader.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.keyTable.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/responsive.bootstrap.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.scroller.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.colVis.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/datatables/dataTables.fixedColumns.min.js')}}"></script>
        <script src="{{asset('panel/assets/pages/datatables.init.js')}}"></script>
        <script src="{{asset('panel/assets/dropify/dist/js/dropify.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/x-editable/js/bootstrap-editable.min.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/notifyjs/js/notify.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/notifications/notify-metro.js')}}"></script>

        <script src="{{asset('panel/assets/plugins/fileinput/js/fileinput.js')}}"></script>
        <script src="{{asset('panel/assets/plugins/fileinput/js/fileinput_locale_ar.js')}}"></script>
        @yield('script')
        <script src="{{asset('panel/assets/js/jquery.core.js')}}"></script>
        <script src="{{asset('panel/assets/js/jquery.app.js')}}"></script>
        <div class="container">
            @include('admin.layouts.alerts')
        </div>
    </body>
</html>