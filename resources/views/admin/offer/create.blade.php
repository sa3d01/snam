@extends('admin.layouts.app')
@section('title',$module_name)
@section('style')
    <link href="{{asset('panel/assets/plugins/summernote/summernote.css')}}" rel="stylesheet" />
    {{--<link href="{{asset('panel/assets/plugins/bootstrap-select/css/bootstrap-select.min.css')}}" rel="stylesheet" />--}}
    {{--<link href="{{asset('panel/assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet" />--}}
    {{--<link href="{{asset('panel/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />--}}
    {{--<link href="{{asset('panel/assets/plugins/multiselect/css/multi-select.css')}}"  rel="stylesheet" type="text/css" />--}}
    {{--<link href="{{asset('panel/assets/plugins/switchery/css/switchery.min.css')}}" rel="stylesheet" />--}}
@stop
@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><i class="icon-pencil before_word"></i>&nbsp;
                                إضافة {{ $single_module_name }}
                            </b>
                            <hr>
                        </h4>
                        {!! Form::open(['method'=>'post', 'files'=>true, 'enctype' => 'multipart/form-data', 'route'=>[$route.'.store'], 'class' => 'form-row-seperated add_ads_form']) !!}
                        {!! Form::hidden('add_by', Auth::user()->id) !!}
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($create_fields as $labels => $fields)
                                    @php $s1=$fields; $s2="id";$s3="company_id" @endphp
                                    @if($fields=='image' || $fields=='logo')
                                        <div class="white-box">
                                            <label for="input-file-now-custom-1">{{ $labels }}</label>
                                            <input name="{{$fields}}" type="file" id="input-file-now-custom-1" class="dropify" data-default-file="{{asset('images/logo/default.png')}}"/>
                                            @if ($errors->has($fields))
                                                <small class="text-danger">{{ $errors->first($fields) }}</small>
                                            @endif
                                        </div>
                                        <br>
                                    @elseif($labels=='المتجر')
                                        <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                            <label for="title">{{ $labels }}</label>
                                            {{ Form::select($fields, $shop, null, array('class' => 'form-control')) }}
                                        </div>
                                        <br>
                                    @elseif($fields=='note_ar' || $fields=='note_en')
                                        <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                            <label for="title">{{ $labels }}</label>
                                            {{ Form::textarea($fields, null, array('class' => 'form-control')) }}
                                        </div>
                                        <br>
                                    @else
                                        <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                            <label for="title">{{ $labels }}</label>
                                            {!! Form::text($fields, null, ['class'=>'form-control']) !!}
                                        </div>
                                        <br>
                                    @endif
                                @endforeach
                                    <div class="form-group">
                                        <label class="control-label">الشركات المقدم لها العرض</label>
                                        <div class="input-group">
                                            {{ Form::select('companies[]', $company, null, array('class' => 'form-control multi-select','id'=>'my_multi_select1', 'data-plugin'=>'multiselect','multiple'=>'multiple')) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">الباقات المقدم لها العرض</label>
                                        <div class="input-group">
                                            {{ Form::select('packages[]', $package, null, array('class' => 'form-control multi-select','id'=>'my_multi_select1', 'data-plugin'=>'multiselect','multiple'=>'multiple')) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">موعد بداية العرض</label>
                                        <div class="input-group">
                                            <input required type="text" name="start_date" class="form-control" placeholder="mm/dd/yyyy" id="start_date">
                                            <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">موعد نهاية العرض</label>
                                        <div class="input-group">
                                            <input required type="text" name="end_date" class="form-control" placeholder="mm/dd/yyyy" id="end_date">
                                            <span class="input-group-addon bg-custom b-0 text-white"><i class="icon-calender"></i></span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="control-label col-md-push-1">
                                            <button type="submit" class="update_button btn btn-success btn-rounded waves-effect waves-light">
                                                إضافة
                                            </button>
                                        </div>
                                    </div>
                            </div>

                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $( "#start_date" ).datepicker();
            $( "#end_date" ).datepicker();
            // Basic
            $('.dropify').dropify();
            // Translated
            $('.dropify-fr').dropify({
                messages: {
                    default: 'Glissez-déposez un fichier ici ou cliquez',
                    replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
                    remove: 'Supprimer',
                    error: 'Désolé, le fichier trop volumineux'
                }
            });
            // Used events
            var drEvent = $('#input-file-events').dropify();
            drEvent.on('dropify.beforeClear', function(event, element) {
                return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
            });
            drEvent.on('dropify.afterClear', function(event, element) {
                alert('File deleted');
            });
            drEvent.on('dropify.errors', function(event, element) {
                console.log('Has Errors');
            });
            var drDestroy = $('#input-file-to-destroy').dropify();
            drDestroy = drDestroy.data('dropify')
            $('#toggleDropify').on('click', function(e) {
                e.preventDefault();
                if (drDestroy.isDropified()) {
                    drDestroy.destroy();
                } else {
                    drDestroy.init();
                }
            })
        });
    </script>
    <script src="{{ url('panel/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{url('panel/assets/plugins/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{url('panel/assets/pages/jquery.form-pickers.init.js')}}"></script>

    <script src="{{url('panel/assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js')}}"></script>
    <script src="{{url('panel/assets/plugins/switchery/js/switchery.min.js')}}"></script>
    <script type="text/javascript" src="{{url('panel/assets/plugins/multiselect/js/jquery.multi-select.js')}}"></script>
    <script type="text/javascript" src="{{url('panel/assets/plugins/jquery-quicksearch/jquery.quicksearch.js')}}"></script>
    <script src="{{url('panel/assets/plugins/select2/js/select2.min.js')}}" type="text/javascript"></script>
    <script src="{{url('panel/assets/plugins/bootstrap-select/js/bootstrap-select.min.js')}}" type="text/javascript"></script>
    <script src="{{url('panel/assets/plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js')}}" type="text/javascript"></script>
    <script src="{{url('panel/assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js')}}" type="text/javascript"></script>
    <script src="{{url('panel/assets/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js')}}" type="text/javascript"></script>
@stop