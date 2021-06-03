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
                        تعديل بيانات {{ $single_module_name }}
                    </b>
                    <hr>
                </h4>
                {!! Form::model($page, ['method'=>'post','name'=>'update', 'files'=>true, 'route'=>['page.update', $page->id], 'class' => 'form-horizontal form-row-seperated']) !!}
                    {!! Form::hidden('id', $page->id) !!}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="title">العنوان </label>
                                {!! Form::text('title', $page->title, ['class'=>'form-control','required']) !!}
                            </div>
                            <div class="form-group">
                                <label for="content">النص</label>
                                {!! Form::textarea('content', $page->content , ['class'=>'form-control']) !!}
                            </div>
                            <div class="form-group">
                                <div class="fileupload btn btn-purple waves-effect waves-light">
                                    <span><i class="ion-upload m-r-12"></i>الصور المرفقة</span>
                                    <input id="uploadFile" class="upload" type="file" accept="image/*" name="images[]" multiple />
                                    <br/>
                                    <div class="form-group" id="image_preview">
                                        @if($page->images!=null)
                                            @php($images=json_decode($page->images))
                                            @foreach($images as $image)
                                                <img style='pointer-events: none;max-height: 100px;max-width: 100px;height: 100px;border-radius: 10px;margin: 5px;' src='{{$image}}'>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="form-group">
                        <div class="control-label col-md-push-1">
                            <button type="submit" class="update_button btn btn-success btn-rounded waves-effect waves-light">
                                تعديل
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
    <script>
        $("#uploadFile").change(function(){
            $('#image_preview').html("");
            var total_file=document.getElementById("uploadFile").files.length;
            for(var i=0;i<total_file;i++)
            {
                $('#image_preview').append("<img style='pointer-events: none;max-height: 100px;max-width: 100px;height: 100px;border-radius: 10px;margin: 5px;' src='"+URL.createObjectURL(event.target.files[i])+"'>");
            }
        });
    </script>
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
