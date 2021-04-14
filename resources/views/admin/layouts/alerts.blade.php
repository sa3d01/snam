@if (session()->has('updated'))
    <script>
        $.Notification.notify('success','top left','تم التعديل بنجاح','تم تعديل البيانات بقاعدة بياناتك');
    </script>
@elseif (session()->has('created'))
    <script>
        $.Notification.notify('success','top left','تمت الإضافة بنجاح','تم اضافة البيانات بقاعدة بياناتك');
    </script>
@elseif (session()->has('deleted'))
    <script>
        $.Notification.notify('success','top left','تم الحذف بنجاح');
    </script>
@elseif (session()->has('notice'))
    <script>
        $.Notification.notify('success','top left','تم التعديل بنجاح');
    </script>
@elseif (session()->has('status'))
    <script>
        $.Notification.notify('success','top left','تم التعديل بنجاح');
    </script>
@endif
