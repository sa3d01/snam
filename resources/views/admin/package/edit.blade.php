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
                {!! Form::model($row, ['method'=>'patch','name'=>'update', 'files'=>true, 'route'=>[$route.'.update', $row->id], 'class' => 'form-horizontal form-row-seperated']) !!}
                    {!! Form::hidden('id', $row->id) !!}
                    <div class="row">
                        <div class="col-md-12">
                            @foreach($update_fields as $labels => $fields)
                                @php $s1=$fields; $s2="id"; @endphp
                                @if($fields=='image')
                                    <div class="white-box">
                                        <label for="input-file-now-custom-1">{{ $labels }}</label>
                                        <input name="{{$fields}}" type="file" id="input-file-now-custom-1" class="dropify" data-default-file="{{asset('images/'.$route.'/'.$row->image) }}"/>
                                        @if ($errors->has($fields))
                                            <small class="text-danger">{{ $errors->first($fields) }}</small>
                                        @endif
                                    </div>
                                    <br>
                                @elseif($fields=='logo')
                                    <div class="white-box">
                                        <label for="input-file-now-custom-1">{{ $labels }}</label>
                                        <input name="{{$fields}}" type="file" id="input-file-now-custom-1" class="dropify" data-default-file="{{asset('images/'.$route.'/'.$row->logo) }}"/>
                                        @if ($errors->has($fields))
                                            <small class="text-danger">{{ $errors->first($fields) }}</small>
                                        @endif
                                    </div>
                                    <br>
                                @elseif(substr($s1, -strlen($s2))==$s2)
                                    <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                        <label for="title">{{ $labels }}</label>
                                        {{ Form::select($fields, $category, null, array('class' => 'form-control')) }}
                                    </div>
                                @else
                                    <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                        <label for="title">{{ $labels }}</label>
                                        {!! Form::text($fields, null, ['class'=>'form-control']) !!}
                                        @if ($errors->has($fields))
                                            <small class="text-danger">{{ $errors->first($fields) }}</small>
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                            @if($language==2)
                                @foreach($descriptions as $label=>$description)
                                    @foreach($languages as $lang)
                                            <div class="form-group">
                                                <label for="title">{{ $label .' '.'بـ' .$lang->name }}</label>
                                                @php($d=$modelDescription->where(['language_id'=>$lang->id,$route.'_id'=>$row->id])->value($description))
                                                @if($description=='note')
                                                    {!! Form::textarea($description.'_'.$lang->label, $d, ['class'=>'form-control']) !!}
                                                @else
                                                    {!! Form::text($description.'_'.$lang->label, $d, ['class'=>'form-control']) !!}
                                                @endif
                                                @if ($errors->has($description))
                                                    <small class="text-danger">{{ $errors->first($description) }}</small>
                                                @endif
                                            </div>
                                    @endforeach
                                @endforeach
                            @endif
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
    <script src="{{asset('panel/assets/plugins/summernote/summernote.min.js')}}"></script>


    <script>
        jQuery(document).ready(function() {
            var id = '{{$row->id}}';
            $.ajax({
                type:"GET",
                url:'{{asset('/showImg')}}'+'/'+id,
                success:function(msg){
                    $('#showImg').html('');
                    $('#showImg').html(msg);
                },error: function (msg) {
                    alert(msg)
                }
            });

        });
    </script>

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
    <script>
        $(document).ready(function() {
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
@stop