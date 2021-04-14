@extends('admin.layouts.app')
@section('title',$module_name)
@section('style')
    <link href="{{asset('panel/assets/plugins/summernote/summernote.css')}}" rel="stylesheet" />
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
                        {!! Form::hidden('collective_notice','true') !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div>
                                    <label for="title">  نص الإشعار</label>
                                    {!! Form::textarea('note', null , ['class'=>'form-control']) !!}
                                    @if ($errors->has('note'))
                                        <small class="text-danger">{{ $errors->first('note') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="control-label col-md-push-1">
                                <button type="submit" class="update_button btn btn-success btn-rounded waves-effect waves-light">
                                    إضافة
                                </button>
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
    <script src="{{asset('panel/assets/plugins/summernote/summernote.min.js')}}"></script>
    <script>
        jQuery(document).ready(function(){
            $('.summernote').summernote({
                height: 350,                 // set editor height
                minHeight: null,             // set minimum height of editor
                maxHeight: null,             // set maximum height of editor
                focus: false                 // set focus to editable area after initializing summernote
            });
            $('.inline-editor').summernote({
                airMode: true
            });
        });
    </script>
@stop
