@extends('admin.layouts.app')
@section('title',$module_name)
@section('style')
    <link href="{{asset('panel/assets/plugins/summernote/summernote.css')}}" rel="stylesheet"/>
    <!--morooo-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css"
          integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz"
          crossorigin="anonymous"><!--morooo-->
@stop
@section('content')
    <div class="content">
        <div class="container">
            <div class="col-lg-12">

                <div class="row">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><i class="icon-pencil before_word"></i>&nbsp;
                                تعديل بيانات {{ $module_name }}
                            </b>
                            <hr>
                        </h4>
                        {!! Form::model($row, ['method'=>'patch','name'=>'update', 'files'=>true, 'route'=>[$route.'.update_setting', $row->id], 'class' => 'form-horizontal form-row-seperated']) !!}
                        {!! Form::hidden('id', $row->id) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
{{--                                    <div>--}}
{{--                                        <label for="title">الشروط والاحكام</label>--}}
{{--                                        {!! Form::textarea('licence', \App\Models\Page::where('name','licence')->value('content') , ['class'=>'form-control']) !!}--}}
{{--                                        @if ($errors->has('licence'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('licence') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title">من نحن</label>--}}
{{--                                        {!! Form::textarea('about', \App\Models\Page::where('name','about')->value('content') , ['class'=>'form-control']) !!}--}}
{{--                                        @if ($errors->has('about'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('about') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title"> السلع الممنوعة</label>--}}
{{--                                        {!! Form::textarea('block', $row->block , ['class'=>'form-control']) !!}--}}
{{--                                        @if ($errors->has('block'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('block') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title">عمولة التطبيق</label>--}}
{{--                                        {!! Form::textarea('percent', \App\Models\Page::where('name','percent')->value('content') , ['class'=>'form-control']) !!}--}}
{{--                                        @if ($errors->has('percent'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('percent') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title">الابل فى القرآن</label>--}}
{{--                                        {!! Form::textarea('quran', \App\Models\Page::where('name','quran')->value('content') , ['class'=>'form-control']) !!}--}}
{{--                                        @if ($errors->has('quran'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('quran') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title">الابل فى الحديث</label>--}}
{{--                                        {!! Form::textarea('hadeth', \App\Models\Page::where('name','hadeth')->value('content') , ['class'=>'form-control summernote']) !!}--}}
{{--                                        @if ($errors->has('hadeth'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('hadeth') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title">قيل فى الإبل</label>--}}
{{--                                        {!! Form::textarea('talk_about', \App\Models\Page::where('name','talk_about')->value('content') , ['class'=>'form-control summernote']) !!}--}}
{{--                                        @if ($errors->has('talk_about'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('talk_about') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title">زكاة الإبل</label>--}}
{{--                                        {!! Form::textarea('zakah', $row->zakah , ['class'=>'form-control summernote']) !!}--}}
{{--                                        @if ($errors->has('zakah'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('zakah') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}
{{--                                    <div>--}}
{{--                                        <label for="title">فعاليات ومهرجانات</label>--}}
{{--                                        {!! Form::textarea('festival', $row->festival , ['class'=>'form-control summernote']) !!}--}}
{{--                                        @if ($errors->has('festival'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('festival') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <hr>--}}

{{--                                    <div>--}}
{{--                                        <label for="title">نص تواصل معنا</label>--}}
{{--                                        {!! Form::textarea('contact', \App\Models\Page::where('name','contact')->value('content') , ['class'=>'form-control summernote']) !!}--}}
{{--                                        @if ($errors->has('contact'))--}}
{{--                                            <small class="text-danger">{{ $errors->first('contact') }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
                                    <hr>
                                    <div>
                                        <label>رقم الهاتف </label>
                                        <input class="form-control" type="tel" name="mobile"
                                               value="{{$row->mobile}}">
                                    </div>
                                    <div>
                                        <label>نسبة التطبيق</label>
                                        <input class="form-control" type="number" name="percent_ratio"
                                               value="{{$row->percent_ratio}}">
                                    </div>
                                    <div class="form-group col-md-12 ">
                                        <div>
                                            <label>رابط تويتر</label>
                                            <input class="form-control" type="url" name="twitter"
                                                   value="{{\App\Models\Setting::value('twitter')}}">
                                        </div>
                                        <div>
                                            <label>رابط انستجرام</label>
                                            <input class="form-control" type="url" name="instagram"
                                                   value="{{\App\Models\Setting::value('instagram')}}">
                                        </div>
                                        <div>
                                            <label>رابط فيسبوك</label>
                                            <input class="form-control" type="url" name="facebook"
                                                   value="{{\App\Models\Setting::value('facebook')}}">
                                        </div>
                                        <div>
                                            <label>رابط سناب شات</label>
                                            <input class="form-control" type="url" name="snap"
                                                   value="{{\App\Models\Setting::value('snap')}}">
                                        </div>
                                        <div>
                                            <label>رابط يوتيوب</label>
                                            <input class="form-control" type="url" name="youtube"
                                                   value="{{\App\Models\Setting::value('youtube')}}">
                                        </div>
                                        <div>
                                            <label>رابط جوجل بﻻى</label>
                                            <input class="form-control" type="url" name="android"
                                                   value="{{\App\Models\Setting::value('android')}}">
                                        </div>
                                        <div>
                                            <label>رابط ابل استور</label>
                                            <input class="form-control" type="url" name="ios"
                                                   value="{{\App\Models\Setting::value('ios')}}">
                                        </div>
                                    </div>



                                    <div class="row-buttons">


                                        <div class="form-group ree-locat">
                                            <div class="control-label col-md-push-1">
                                                <button type="submit"
                                                        class="update_button btn btn-success btn-rounded waves-effect waves-light">
                                                    تعديل
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
        </div>
        @endsection
        @section('script')

            <script type="text/javascript">
                $(document).ready(function () {
                    for (var i = 1; i < 21; i++) {
                        $.each([i], function (a, l) {
                            console.log(l)
                            var max_fields = 20; //maximum input boxes allowed
                            var wrapper = $(".input_fields_wrap" + l); //Fields wrapper
                            var add_button = $(".add_field_button" + l); //Add button ID


                            var x = 1; //initlal text box count
                            $(add_button).click(function (e) { //on add input button click
                                e.preventDefault();
                                if (x < max_fields) { //max input box allowed
                                    x++; //text box increment
                                    if (l === 1) {
                                        $(wrapper).append('<div><input type="text" name="deadlines[]" value=""/><a href="#" class="remove_field"><!--morooo--><i class="fas fa-trash-alt delete-trash"></i><!--morooo--></a></div>'); //add input box
                                    } else if (l === 2) {
                                        $(wrapper).append('<div><input type="text" name="main_prices[]"/><a href="#" class="remove_field"><!--morooo--><i class="fas fa-trash-alt delete-trash"></i><!--morooo--></a></div>'); //add input box
                                    } else if (l === 3) {
                                        $(wrapper).append('<div><input type="text" name="sub_prices[]"/><a href="#" class="remove_field"><!--morooo--><i class="fas fa-trash-alt delete-trash"></i><!--morooo--></a></div>'); //add input box
                                    } else if (l === 4) {
                                        $(wrapper).append('<div><input type="text" name="sizes[]"/><a href="#" class="remove_field"><!--morooo--><i class="fas fa-trash-alt delete-trash"></i><!--morooo--></a></div>'); //add input box
                                    }
                                }
                            });

                            $(wrapper).on("click", ".remove_field", function (e) { //user click on remove text
                                e.preventDefault();
                                $(this).parent('div').remove();
                                x--;
                            })
                        });
                    }
                });
            </script>
            <script>
                $(document).ready(function () {
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
                    drEvent.on('dropify.beforeClear', function (event, element) {
                        return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                    });
                    drEvent.on('dropify.afterClear', function (event, element) {
                        alert('File deleted');
                    });
                    drEvent.on('dropify.errors', function (event, element) {
                        console.log('Has Errors');
                    });
                    var drDestroy = $('#input-file-to-destroy').dropify();
                    drDestroy = drDestroy.data('dropify')
                    $('#toggleDropify').on('click', function (e) {
                        e.preventDefault();
                        if (drDestroy.isDropified()) {
                            drDestroy.destroy();
                        } else {
                            drDestroy.init();
                        }
                    })
                });
            </script>
            <script src="{{asset('panel/assets/plugins/summernote/summernote.min.js')}}"></script>
            <script>
                jQuery(document).ready(function () {
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
