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
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade in" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        @endif
                        {!! Form::open(['method'=>'post', 'files'=>true, 'enctype' => 'multipart/form-data', 'route'=>[$route.'.store'], 'class' => 'form-row-seperated add_ads_form']) !!}
                        {!! Form::hidden('add_by', Auth::user()->id) !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="title">النوع</label>
                                    {{ Form::select('type', ['city'=>'مدينة','district'=>'منطقة'], null, array('class' => 'form-control','id'=>'type')) }}
                                </div>
                                <br>
                                <div class="form-group" id="districts">
                                    <label for="title">المناطق</label>
                                    {{ Form::select('district_id', $district, null, array('class' => 'form-control')) }}
                                </div>
                                <br>
                                @foreach($index_fields as $labels => $fields)
                                    <div class="form-group{{ $errors->has($fields) ? ' has-error' : '' }}">
                                        <label for="title">{{ $labels }}</label>
                                        {!! Form::text($fields, null, ['class'=>'form-control']) !!}
                                    </div>
                                    <br>
                                @endforeach
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
            $('#type').on('change', function (e) {
                e.preventDefault();
                var type = $(this).val();
                if (type==='city'){
                    $('#districts').show();
                }else{
                    $('#districts').hide();
                }

            });
        });
    </script>
@stop
