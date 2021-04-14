@extends('admin.layouts.app')
@section('title',$module_name)
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
                        <div class="row">
                            <div class="col-md-6">
                                @foreach($create_fields as $labels => $fields)
                                    @php $s1=$fields; $s2="id"; @endphp

                                    @if(substr($s1, -strlen($s2))==$s2)
                                        <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                            <label for="title">{{ $labels }}</label>
                                            {{ Form::select($fields, $type, null, array('class' => 'form-control')) }}

                                        </div>
                                    @else
                                        <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                            <label for="title">{{ $labels }}</label>
                                            {!! Form::text($fields, null, ['class'=>'form-control']) !!}

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

                                <div class="form-group">
                                    <label class="control-label">التصاريح</label>
                                    <br>
                                    <select class="form-control" name="role">
                                        @foreach($roles as  $role)
                                            <option value="{{$role->id}}">{{$role->name}}</option>
                                        @endforeach
                                    </select>
                                    @if ($errors->has('status'))
                                        <small class="text-danger">{{ $errors->first('status') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="white-box">
                                    <label for="input-file-now-custom-1">الصورة الشخصية</label>
                                    <input name="image" type="file" id="input-file-now-custom-1" class="dropify"
                                           data-default-file="{{asset('images/user/default.png')}}"/>
                                    @if ($errors->has('image'))
                                        <small class="text-danger">{{ $errors->first('image') }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="control-label col-md-push-1">
                                <button type="submit"
                                        class="update_button btn btn-success btn-rounded waves-effect waves-light">
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
@stop