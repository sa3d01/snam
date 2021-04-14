@extends('admin.layouts.app')
@section('title',$module_name)
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
                        <div class="col-md-6">
                            @foreach($update_fields as $labels => $fields)
                                @php $s1=$fields; $s2="id"; @endphp
                                @if($fields=='city_id')
                                    <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                        <label for="title">{{ $labels }}</label>
                                        {{ Form::select($fields, $city, null, array('class' => 'form-control')) }}
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
                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="title">كلمة المرور</label>
                                    {!! Form::password( 'password', ['class'=>'form-control']) !!}
                                    @if ($errors->has('password'))
                                        <small class="text-danger">{{ $errors->first('password') }}</small>
                                    @endif
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="white-box">
                                <label for="input-file-now-custom-1">الصورة الشخصية</label>
                                <input name="image" type="file" id="input-file-now-custom-1" class="dropify" @if($row->image) data-default-file="{{$row->image}}" @else data-default-file="{{asset('images/user/default.png')}}"  @endif/>
                                @if ($errors->has('image'))
                                    <small class="text-danger">{{ $errors->first('image') }}</small>
                                @endif
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
    {{--<script>--}}
        {{--$(document).on('click', '.update_button', function (e) {--}}
            {{--e.preventDefault();--}}
            {{--swal({--}}
                    {{--title: "هل انت متأكد من التعديل ؟",--}}
                    {{--type: "warning",--}}
                    {{--showCancelButton: true,--}}
                    {{--confirmButtonClass: 'btn-danger',--}}
                    {{--confirmButtonText: 'نعم , قم بالتعديل!',--}}
                    {{--cancelButtonText: 'ﻻ , الغى العملية !',--}}
                    {{--closeOnConfirm: false,--}}
                    {{--closeOnCancel: false--}}
                {{--},--}}
                {{--function (isConfirm) {--}}
                    {{--if (isConfirm) {--}}
                        {{--swal("تم التعديل بنجاح !", "", "success");--}}
                        {{--$("form[name='update']").submit();--}}
                    {{--} else {--}}
                        {{--swal("تم الالغاء", "", "error");--}}
                    {{--}--}}
                {{--});--}}
        {{--});--}}
    {{--</script>--}}
@stop