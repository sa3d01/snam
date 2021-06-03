<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="sidebar-inner slimscrollleft">
        <!--- Divider -->
        <div id="sidebar-menu">
            <ul>
                <li>
                    <a href="{{url('admin')}}" class="waves-effect"><i class="ti-home"></i> <span> لوحة التحكم </span></a>
                </li>

                <li class="text-muted menu-title"></li>
                <li class="has_sub">
                    <a href="{{route('setting.get_setting')}}" class="waves-effect"><i class="md md-settings"></i><span> الإعدادات </span></a>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i
                            class="fa fa-pagelines"></i><span>الصفحات التعريفية </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('edit_page','about')}}">عن التطبيق</a></li>
                        <li><a href="{{route('edit_page','licence')}}">الشروط والأحكام</a></li>
                        <li><a href="{{route('edit_page','contact')}}">تواصل معنا</a></li>
                        <li><a href="{{route('edit_page','block')}}">السلع الممنوعة</a></li>
                        <li><a href="{{route('edit_page','percent')}}">عمولة التطبيق</a></li>
                        <li><a href="{{route('edit_page','quran')}}">الإبل فى القران</a></li>
                        <li><a href="{{route('edit_page','hadeth')}}">الإبل فى الحديث</a></li>
                        <li><a href="{{route('edit_page','zakah')}}">زكاة الإبل</a></li>
                        <li><a href="{{route('edit_page','talk_about')}}">قيل فى الإبل</a></li>
                        <li><a href="{{route('edit_page','festival')}}">فعاليات ومهرجانات</a></li>
                        <li><a href="{{route('edit_page','news')}}">الأخبار</a></li>
                        <li><a href="{{route('edit_page','wsoom')}}">وسوم</a></li>
                    </ul>
                </li>

                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-alert"></i><span> الإشعارات الجماعية </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('notification.collective_notice')}}">عرض</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-user"></i><span>ادارة الصلاحيات  </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('roles.create')}}">إضافة صلاحية جديده</a></li>
                        <li><a href="{{route('roles.index')}}">عرض الكل</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-crown"></i><span
                                class="label label-info pull-right">{{$admin_count}}</span><span> الإدارة </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('admin.create')}}">إضافة مدير جديد</a></li>
                        <li><a href="{{route('admin.index')}}">عرض الكل</a></li>
                    </ul>
                </li>
                <li class="text-muted menu-title"></li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i
                                class="fa fa-user"></i><span>ادارة العملاء  </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('user.active_users')}}">العملاء المفعلين</a></li>
                        <li><a href="{{route('user.not_active_users')}}">العملاء المحظورين</a></li>
                        <li><a href="{{route('notification.create')}}">اشعارات جماعية</a></li>
                        <li><a href="{{route('user.create')}}">اضافة عميل</a></li>
                    </ul>
                </li>
                <li class="text-muted menu-title"></li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-panel"></i><span class="label label-warning pull-right">{{$bank_count}}</span><span> الحسابات البنكية </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('bank.create')}}">إضافة حساب جديد</a></li>
                        <li><a href="{{route('bank.index')}}">عرض الكل</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="ti-panel"></i><span> بانرات الصفحة الرئيسية </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('slider.index')}}">عرض الكل</a></li>
                        <li><a href="{{route('slider.create')}}">إضافة</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md-local-gas-station"></i><span
                                class="label label-info pull-right">{{$city_count}}</span><span> المدن والمناطق </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('city.create')}}">اضافة جديد</a></li>
                        <li><a href="{{route('city.index')}}">عرض المدن</a></li>
                        <li><a href="{{route('district.index')}}">عرض المناطق</a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-message"></i><span
                                class="label label-info pull-right">{{$non_read_contacts_count}}</span><span> اتصل بنا </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('contact.index')}}">عرض الكل</a></li>
                    </ul>
                </li>
                <li class="text-muted menu-title"></li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-call-split"></i><span> الأقسام  </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('category.create')}}">إضافة</a></li>
                        <li><a href="{{route('category.index')}}">عرض </a></li>
                    </ul>
                </li>
                <li class="has_sub">
                    <a href="javascript:void(0);" class="waves-effect"><i class="md md-computer"></i><span> الإعلانات  </span></a>
                    <ul class="list-unstyled">
                        <li><a href="{{route('ad.create')}}">إضافة</a></li>
                        <li><a href="{{route('ad.index')}}">عرض </a></li>
                    </ul>
                </li>
                <li class="text-muted menu-title"></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<!-- Left Sidebar End -->
