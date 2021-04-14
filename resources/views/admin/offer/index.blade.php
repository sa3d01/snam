@extends('admin.layouts.app')
@section('title',$module_name)
@section('content')
    <div class="content">
        <div class="container">
            <!-- Page-Title -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card-box table-responsive">
                        <table id="datatable-buttons" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                @foreach($index_fields as $labels => $fields)
                                    <th> {{ $labels }} </th>
                                @endforeach
                                <th> QR </th>
                                <th> التحكم </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rows as $row)
                                <tr>
                                    @foreach($index_fields as $labels => $fields)
                                        @php $s1=$fields; $s2="id"; @endphp
                                        @if($row->$fields==null)
                                            <td> ﻻ يوجد </td>
                                        @elseif(strpos($row->$fields, '.png') || strpos($row->$fields, '.jpg') || strpos($row->$fields, '.jpeg'))
                                            @if($row->image)
                                                <td data-toggle="modal" data-target="#myModal{{$row->id}}"> <img class="img_preview" src="{{ asset('images/'.$route.'/'.$row->image)}}" style="height: 200px;width: 300px;"> </td>
                                                <div id="myModal{{$row->id}}" class="modal fade" role="img">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <img data-toggle="modal" data-target="#myModal{{$row->id}}" class="img-preview" src="{{ asset('images/'.$route.'/'.$row->image)}}" style="max-height: 500px">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <td data-toggle="modal" data-target="#myModal{{$row->id}}"> <img class="img_preview" src="{{ asset('images/'.$route.'/'.$row->logo) }}" style="height: 200px;width: 300px;"> </td>
                                                <div id="myModal{{$row->id}}" class="modal fade" role="img">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-body">
                                                                <img data-toggle="modal" data-target="#myModal{{$row->id}}" class="img-preview" src="{{ asset('images/'.$route.'/'.$row->logo) }}" style="max-height: 500px">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @elseif(substr($s1, -strlen($s2))==$s2)

                                            <td>{{\App\Models\ShopDescription::where(['language_id'=>1,'shop_id'=>$row->shop_id])->value('name')}}</td>
                                        @else
                                            <td> {{ $row->$fields }} </td>
                                        @endif
                                    @endforeach
                                    {{--qr--}}
                                    <td data-toggle="modal" data-target="#myModal{{$row->id}}">
                                        <img style="text-align: center" src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(100)->generate('entmaa_'.$row->shop_id))!!} ">
                                    </td>
                                    <div id="myModal{{$row->id}}" class="modal fade" role="img">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <img data-toggle="modal" data-target="#myModal{{$row->id}}" class="img-preview" style="text-align: center" src="data:image/png;base64,{!! base64_encode(QrCode::format('png')->size(100)->generate('entmaa_'.$row->shop_id))!!} ">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{--/qr--}}
                                    <td class="actions">
                                        <div class="row" style="margin-top: 20px">
                                            <div class="col-md-3">
                                                {!! Form::open(['method' => 'DELETE','data-id'=>$row->id, 'route' => [$route.'.destroy',$row->id],'class'=>'delete']) !!}
                                                {!! Form::hidden('id', $row->id) !!}
                                                <button type="button"  class="btn btn-danger btn-custom waves-effect waves-light"><i class="fa fa-trash"></i></button>
                                                {!! Form::close() !!}
                                            </div>
                                            <div class="col-md-3">
                                                <a href="{{route($route.'.edit',$row->id)}}">
                                                    <button type="button" class="btn btn-info btn-custom waves-effect waves-light"><i class="fa fa-edit"></i></button>
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- end row -->
        </div> <!-- container -->
    </div> <!-- content -->
@stop
@section('script')
    <script type="text/javascript">
        $(document).ready(function () {

            $('#datatable').dataTable();
            $('#datatable-keytable').DataTable({keys: true});
            $('#datatable-responsive').DataTable();
            $('#datatable-colvid').DataTable({
                "dom": 'C<"clear">lfrtip',
                "colVis": {
                    "buttonText": "Change columns"
                }
            });
            $('#datatable-scroller').DataTable({
                ajax: "public/panel/assets/plugins/datatables/json/scroller-demo.json",
                deferRender: true,
                scrollY: 380,
                scrollCollapse: true,
                scroller: true
            });
            var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
            var table = $('#datatable-fixed-col').DataTable({
                scrollY: "300px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                }
            });
        });
        TableManageButtons.init();

    </script>

    <script>
        $(document).on('click', '.delete', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                    title: "هل انت متأكد من الحذف ؟",
                    text: "لن تستطيع استعادة هذا العنصر مرة أخرى!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-danger',
                    confirmButtonText: 'نعم , قم بالحذف!',
                    cancelButtonText: 'ﻻ , الغى عملية الحذف!',
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function (isConfirm) {
                    if (isConfirm) {
                        swal("تم الحذف !", "تم حذف العنصر من قاعدة البيانات!", "success");
                        $("form[data-id='"+id+"']").submit();
                    } else {
                        swal("تم الالغاء", "ما زال العنصر متاح بقاعدة بياناتك :)", "error");
                    }
                });
        });
    </script>

@endsection
