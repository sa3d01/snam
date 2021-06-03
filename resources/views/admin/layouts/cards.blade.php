<div class="row">
    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-account-child text-custom"></i>
            <h2 class="m-0 text-dark counter font-600">{{$active_user_count}}</h2>
            <div class="text-muted m-t-5">المستخدمين النشطاء</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-block text-custom"></i>
            <h2 class="m-0 text-dark counter font-600">{{$not_active_user_count}}</h2>
            <div class="text-muted m-t-5">المستخدمين المحظورين</div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-message text-primary"></i>
            <h2 class="m-0 text-dark counter font-600">{{$read_contacts_count}}</h2>
            <div class="text-muted m-t-5">الرسائل المقروءة</div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-subtitles text-primary"></i>
            <h2 class="m-0 text-dark counter font-600">{{$non_read_contacts_count}}</h2>
            <div class="text-muted m-t-5">الرسائل غير المقروءة</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-local-airport text-pink"></i>
            <h2 class="m-0 text-dark counter font-600">{{$city_count}}</h2>
            <div class="text-muted m-t-5">المدن</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-subtitles text-primary"></i>
            <h2 class="m-0 text-dark counter font-600">{{$active_ad_count}}</h2>
            <div class="text-muted m-t-5"> الإعلانات الجارية</div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-add-box text-primary"></i>
            <h2 class="m-0 text-dark counter font-600">{{$not_active_ad_count}}</h2>
            <div class="text-muted m-t-5">الإعلانات المنتهية</div>
        </div>
    </div>

    <div class="col-lg-3 col-sm-6">
        <div class="widget-panel widget-style-2 bg-white">
            <i class="md md-computer text-pink"></i>
            <h2 class="m-0 text-dark counter font-600">{{$admin_count}}</h2>
            <div class="text-muted m-t-5">الإدارة</div>
        </div>
    </div>
</div>
