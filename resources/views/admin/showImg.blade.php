@if(count($photos))
    @foreach($photos as $photo)
        <div class="col-md-3">
            <img src="{{asset('images/shop/').'/'. $photo}}" class="img-responsive" width="200px" height="300px">
            <a class="btn imgDelete" data-id="{{$id}}" data-name="{{$photo}}" style="width: 30px; height: 30px; background-color: white;color: red; border-color: #d43f3a" >X</a>
        </div>
    @endforeach
    <script type="text/javascript">
        $('.imgDelete').on('click', function(e){
            var id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            $.ajax({
                type:"GET",
                url:'{{asset('/deleteImgAd/')}}'+'/'+id+'/'+name,
                success:function(msg){
                    $('#showImg').html('');
                    $('#showImg').html(msg);
                },error:function () {
                    alert('error')
                }
            });
        });
    </script>
@endif