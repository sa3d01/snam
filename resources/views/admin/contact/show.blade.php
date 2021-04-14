@extends('admin.layouts.app')
@section('title',$module_name)
@section('content')
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card-box">
                        <h4 class="m-t-0 header-title"><b><i class="icon-pencil before_word"></i>&nbsp;
                                 بيانات {{ $single_module_name }}
                            </b>
                            <hr>
                        </h4>
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($show_fields as $labels => $fields)
                                    <div class="form-group">
                                        <label for="title">{{ $labels }}</label>
                                        @if($row->$fields==null)
                                            <h3 class="form-control"> ﻻ يوجد</h3>
                                        @elseif($labels=='صاحب السؤال')
                                            <h3 class="form-control">{{\App\User::where(['id'=>$row->$fields])->value('username')}}</h3>
                                        @elseif($labels=='الرسالة')
                                            <div>{{$row->$fields}}</div>
                                        @else
                                            <h3 class="form-control"> {{ $row->$fields }} </h3>
                                        @endif
                                    </div>
                                @endforeach
{{--                                    <label for="title">الردود الادارية</label>--}}
{{--                                    @php--}}
{{--                                    $notes=\App\Models\Notification::where(['reply_type'=>'contact','msg_id'=>$row->id])->get();--}}
{{--                                    @endphp--}}
{{--                                    @foreach($notes as $note)--}}
{{--                                        <h3 class="form-control"> {{$note->note}}</h3>--}}
{{--                                    @endforeach--}}
                            </div>
                            <hr>
{{--                            {!! Form::open(['method'=>'post', 'files'=>true, 'enctype' => 'multipart/form-data', 'route'=>['notification.store'], 'class' => 'form-row-seperated add_ads_form']) !!}--}}
{{--                            {!! Form::hidden('user_id', $row->user_id) !!}--}}
{{--                            {!! Form::hidden('reply_type', 'contact') !!}--}}
{{--                            {!! Form::hidden('msg_id', $row->id) !!}--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-md-12">--}}
{{--                                    <div class="col-md-12 form-group">--}}
{{--                                        <label for="title">الرد</label>--}}
{{--                                        {!! Form::textarea('note', null, ['class'=>'form-control']) !!}--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="form-group">--}}
{{--                                <div class="control-label col-md-push-1">--}}
{{--                                    <button type="submit"--}}
{{--                                            class="update_button btn btn-success btn-rounded waves-effect waves-light">--}}
{{--                                        إرسال رد--}}
{{--                                    </button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            {!! Form::close() !!}--}}
                        </div>

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