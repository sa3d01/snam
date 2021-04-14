@extends('admin.layouts.app')
@section('title',$module_name)
@section('style')
    <link href="{{asset('panel/assets/plugins/summernote/summernote.css')}}" rel="stylesheet"/>
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
                                @foreach($index_fields as $labels => $fields)
                                    <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                        <label for="title">{{ $labels }}</label>
                                        {!! Form::text($fields, null, ['class'=>'form-control']) !!}
                                        @if ($errors->has($fields))
                                            <small class="text-danger">{{ $errors->first($fields) }}</small>
                                        @endif
                                    </div>
                                @endforeach

                                    <div class="custm-per">
                                        <h2>
                                            تحديد الصلاحيات
                                        </h2>
                                        <div class="row">
                                            <div class="col-md-12 col-xs-12">
                                                @php($ids=\App\Models\RolePermission::where('role_id',$row->id)->pluck('permission_id')->toArray())
                                                @foreach($permissions as $permission)
                                                    @if(in_array($permission->id,$ids))
                                                        <label class="checkbox-row"><input checked name="permissions[]" type="checkbox" value="{{$permission->id}}">{{$permission->name}}</label><br>
                                                    @else
                                                        <label class="checkbox-row"><input name="permissions[]" type="checkbox" value="{{$permission->id}}">{{$permission->name}}</label><br>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                            </div>
                        </div>
                        <div class="form-group">
                            <div class="control-label col-md-push-1">
                                <button type="submit"
                                        class="update_button btn btn-success btn-rounded waves-effect waves-light">
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
        jQuery(document).ready(function () {
            var id = '{{$row->id}}';
            $.ajax({
                type: "GET",
                url: '{{asset('/showImg')}}' + '/' + id,
                success: function (msg) {
                    $('#showImg').html('');
                    $('#showImg').html(msg);
                }, error: function (msg) {
                    // alert(msg)
                }
            });

        });
    </script>

@stop