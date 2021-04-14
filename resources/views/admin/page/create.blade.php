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
                        {!! Form::hidden('add_by', Auth::user()->id) !!}
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($languages as $language)
                                    <div>
                                        <label for="title">الاسم بـ{{$language->name}}</label>
                                        {!! Form::text('name_'.$language->label, null, ['class'=>'form-control']) !!}
                                        @if ($errors->has('name_'.$language->label))
                                            <small class="text-danger">{{ $errors->first('name_'.$language->label) }}</small>
                                        @endif
                                    </div>
                                @endforeach
                                @foreach($languages as $language)
                                    <div>
                                        <label for="title">تفاصيل الصفحة بـ{{$language->name}}</label>
                                        {!! Form::textarea('description_'.$language->label, null , ['class'=>'form-control summernote']) !!}
                                        @if ($errors->has('description_'.$language->label))
                                            <small class="text-danger">{{ $errors->first('description_'.$language->label) }}</small>
                                        @endif
                                    </div>
                                @endforeach

                                <div class="form-group">
                                    <label class="control-label">الحالة</label>
                                    {{ Form::select('status', $status, null, array('class' => 'form-control select2 select2-hidden-accessible','tabindex'=>-1,'aria-hidden'=>true)) }}
                                    @if ($errors->has('status'))
                                        <small class="text-danger">{{ $errors->first('status') }}</small>
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