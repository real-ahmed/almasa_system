<aside class="sidebar-left border-right bg-white shadow" id="leftSidebar" data-simplebar>
    <a href="#" class="btn collapseSidebar toggle-btn d-lg-none text-muted ml-2 mt-3 fa-solid fa-bars"
       data-toggle="toggle">
        <i class="fa-solid fa-bars"><span class="sr-only"></span></i>
        <i class=""></i>
    </a>
    <nav class="vertnav navbar navbar-light">
        <!-- nav bar -->
        <div class="w-100 mb-4 d-flex">

            <a class="navbar-brand mx-auto mt-2 flex-fill text-center" href="{{route('home')}}">
                <img class="w-75" src="{{getImage(getFilePath('logoIcon') .'/small_logo.png')}}" alt="@lang('image')">
            </a>
        </div>

        @if(auth()->user()->iswarehouseEmployee )
            <div class="btn-box w-100 mt-4">
                <a href="{{route('warehouse.employee.purchase.all')}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <i class="fe fe-shopping-cart fe-12 mr-2"></i><span class="small">@lang('فواتير الشراء')</span>
                </a>
            </div>


            <div class="btn-box w-100 ">
                <a href="{{route("item.request.all")}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block ">
                    <span class="badge badge-pill badge-danger">{{RequestedItemsCount()}}</span>
                    <span class="small">@lang('طلبات قطع الغيار')</span>

                </a>
            </div>


            <div class="btn-box w-100 ">
                <a href="{{route("component.all")}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <span class="small">@lang('قطع الغيار')</span>
                </a>
            </div>
            <div class="btn-box w-100 ">
                <a href="{{route('warehouse.employee.category.all')}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <span class="small">@lang('التصنيفات الاساسية')</span>
                </a>
            </div>
            <div class="btn-box w-100 ">
                <a href="{{route('warehouse.employee.subcategory.all')}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <span class="small">@lang('التصنيفات الفرعية')</span>
                </a>
            </div>
            <div class="btn-box w-100 ">
                <a href="{{route('item.brand.all',['type'=>1])}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <span class="small">@lang('البراندات')</span>
                </a>
            </div>
            <div class="btn-box w-100 ">
                <a href="{{route("warehouse.employee.supplier.all")}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <span class="small">@lang('الموردين')</span>
                </a>
            </div>
        @endif
        @if(auth()->user()->isreceptionist )
            <div class="btn-box w-100 mt-4">
                <a href="{{route('receptionist.screen.receive.model')}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <i class="fe fe-shopping-cart fe-12 mr-2"></i><span class="small">@lang('فاتورة استلام')</span>
                </a>
            </div>


            <div class="btn-box w-100 ">
                <a href="{{route("receptionist.repair.deliver.all",0)}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <i class="fe fe-shopping-cart fe-12 mr-2"></i><span class="small">@lang('فاتورة تسليم')</span>
                </a>
            </div>

            <div class="btn-box w-100 mt-1 ">
                <a href="{{route('receptionist.sale.all')}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <i class="fe fe-shopping-cart fe-12 mr-2"></i><span class="small">@lang('بيع قطع غيار')</span>
                </a>
            </div>

            <div class="btn-box w-100 mt-1 ">
                <a href="{{route('receptionist.sale.screen.model')}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <i class="fe fe-shopping-cart fe-12 mr-2"></i><span class="small">@lang('بيع شاشات')</span>
                </a>
            </div>

            <div class="btn-box w-100 mt-1 ">
                <a href="{{route("receptionist.customer.all")}}" type="button"
                   class="btn mb-2 btn-primary btn-lg btn-block">
                    <i class="fe fe-shopping-cart fe-12 mr-2"></i><span class="small">@lang('العملاء')</span>
                </a>
            </div>

        @endif


        <div class="btn-box w-100  mb-4">
            <a href="{{route('home')}} " type="button"
               class="btn mb-2 btn-primary btn-lg btn-block">
                <i class="fe fe-shopping-cart fe-12 mr-2"></i><span class="small">@lang("لوحة التحكم")</span>
            </a>
        </div>


        @if(auth()->user()->isengineer || auth()->user()->isreceptionist)
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>@lang("الصيانة")</span>
            </p>
            <ul class="navbar-nav flex-fill w-100 mb-2">
                @if(auth()->user()->isreceptionist)
                    <li class="nav-item">
                        <a href="{{route("receptionist.screen.receive.all")}}" aria-expanded="false" class="nav-link">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                            <span class="ml-3 item-text">@lang("وصلات الاستلام")</span>
                        </a>
                    </li>
                @endif


                <li class="nav-item">
                    <a href="{{route('repair.request.all')}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-gears"></i>
                        <span class="ml-3 item-text">@lang("طلبات الصيانة")</span>
                        <span class="badge badge-pill badge-danger">{{RepairRequestsCount()}}</span>

                    </a>
                </li>


                @if(auth()->user()->isreceptionist)
                    <li class="nav-item">
                        <a href="{{route("receptionist.repair.deliver.all",0)}}" aria-expanded="false" class="nav-link">
                            <i class="fa-solid fa-circle-check"></i>
                            <span class="ml-3 item-text">@lang("طلبات التسليم")</span>
                            <span class="badge badge-pill badge-danger">{{DeliverRequestsCount()}}</span>

                        </a>
                    </li>
                @endif

                @if(auth()->user()->isadmin)
                    <li class="nav-item">
                        <a href="{{route('admin.repair.type.all')}}" aria-expanded="false" class="nav-link">
                            <i class="fa-solid fa-grip"></i>
                            <span class="ml-3 item-text">@lang("انواع الصيانة")</span>
                        </a>
                    </li>
                @endif

                <li class="nav-item">
                    <a href="{{route('repair.request.all',1)}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span class="ml-3 item-text">@lang("سجل الصيانة")</span>
                    </a>
                </li>


                @if(auth()->user()->isreceptionist)
                    <li class="nav-item">
                        <a href="{{route("receptionist.repair.deliver.all",1)}}" aria-expanded="false" class="nav-link">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span class="ml-3 item-text">@lang("سجل التسليم")</span>
                        </a>
                    </li>
                @endif


            </ul>
        @endif

        @if(auth()->user()->isreceptionist)
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>@lang("الشاشات")</span>
            </p>
            <ul class="navbar-nav flex-fill w-100 mb-2">


                <li class="nav-item">
                    <a href="{{route('receptionist.screen.all')}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-display"></i>
                        <span class="ml-3 item-text">@lang("الشاشات")</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{route('receptionist.screen.sale.all')}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span class="ml-3 item-text">@lang("شاشات البيع")</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{route('receptionist.sale.screen.all')}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-receipt"></i>
                        <span class="ml-3 item-text">@lang("وصلات بيع الشاشات")</span>
                    </a>
                </li>

                @if(auth()->user()->isreceptionist)
                    <li class="nav-item dropdown">
                        <a href="#screen-category" data-toggle="collapse" aria-expanded="false"
                           class="dropdown-toggle nav-link collapsed">
                            <i class="fa-solid fa-list"></i>
                            <span class="ml-3 item-text">@lang("الانواع")</span>
                        </a>
                        <ul class="list-unstyled pl-4 w-100 collapse" id="screen-category" style="">

                            <li class="nav-item">
                                <a class="nav-link pl-3" href="{{route("item.brand.all",['type'=>2])}}"><span
                                        class="ml-1 item-text">@lang("البراندات")</span></a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link pl-3" href="{{route('receptionist.screen.model.all')}}"><span
                                        class="ml-1 item-text">@lang("الموديلات")</span></a>
                            </li>


                        </ul>
                    </li>
                    @if(auth()->user()->isadmin)
                        <li class="nav-item">
                            <a href="{{route("admin.warehouse.all",['type'=>2])}}" aria-expanded="false"
                               class="nav-link">
                                <i class="fa-solid fa-warehouse"></i>
                                <span class="ml-3 item-text">@lang("المخازن")</span>
                            </a>
                        </li>
                    @endif
                @endif


            </ul>
        @endif

        @if(auth()->user()->isreceptionist)
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>@lang("المخزن")</span>
            </p>
        @endif
        <ul class="navbar-nav flex-fill w-100 mb-2">
            @if(auth()->user()->isreceptionist && !auth()->user()->isadmin)
                <li class="nav-item">
                    <a href="{{route("item.request.all")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-bell"></i>
                        <span class="ml-3 item-text">@lang("طلبات قطع الغيار")</span>
                        <span class="badge badge-pill badge-danger">{{RequestedItemsCount()}}</span>

                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{route("component.all")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-boxes-stacked"></i>
                        <span class="ml-3 item-text">@lang("قطع الغيار")</span>
                    </a>
                </li>
            @endif



            {{--            @if(auth()->user()->iswarehouseemployee)--}}
            {{--                <li class="nav-item dropdown">--}}
            {{--                    <a href="#category" data-toggle="collapse" aria-expanded="false"--}}
            {{--                       class="dropdown-toggle nav-link collapsed">--}}
            {{--                        <i class="fa-solid fa-list"></i>--}}
            {{--                        <span class="ml-3 item-text">@lang("التصنيفات")</span>--}}
            {{--                    </a>--}}
            {{--                    <ul class="list-unstyled pl-4 w-100 collapse" id="category" style="">--}}

            {{--                        <li class="nav-item">--}}
            {{--                            <a class="nav-link pl-3" href="{{route('warehouse.employee.category.all')}}"><span--}}
            {{--                                    class="ml-1 item-text">@lang("التصنيفات الاساسية")</span></a>--}}
            {{--                        </li>--}}

            {{--                        <li class="nav-item">--}}
            {{--                            <a class="nav-link pl-3" href="{{route('warehouse.employee.subcategory.all')}}"><span--}}
            {{--                                    class="ml-1 item-text">@lang("التصنيفات الفرعية")</span></a>--}}
            {{--                        </li>--}}

            {{--                        <li class="nav-item">--}}
            {{--                            <a class="nav-link pl-3" href="{{route('item.brand.all',['type'=>1])}}"><span--}}
            {{--                                    class="ml-1 item-text">@lang("البراندات")</span></a>--}}
            {{--                        </li>--}}

            {{--                    </ul>--}}
            {{--                </li>--}}
            {{--            @endif--}}

            @if(auth()->user()->isadmin)
                <li class="nav-item">
                    <a href="{{route("admin.warehouse.all",['type'=>1])}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-warehouse"></i>
                        <span class="ml-3 item-text">@lang("المخازن")</span>
                    </a>
                </li>
            @endif


            {{--            @if(auth()->user()->iswarehouseemployee)--}}

            {{--                <li class="nav-item">--}}
            {{--                    <a href="{{route("warehouse.employee.supplier.all")}}" aria-expanded="false" class="nav-link">--}}
            {{--                        <i class="fa-solid fa-truck-field"></i>--}}
            {{--                        <span class="ml-3 item-text">@lang("الموردين")</span>--}}
            {{--                    </a>--}}
            {{--                </li>--}}


            {{--                <li class="nav-item">--}}
            {{--                    <a href="{{route("warehouse.employee.purchase.all")}}" aria-expanded="false" class="nav-link">--}}
            {{--                        <i class="fa-solid fa-file-invoice-dollar"></i>--}}
            {{--                        <span class="ml-3 item-text">@lang("فواتير الشراء")</span>--}}
            {{--                    </a>--}}
            {{--                </li>--}}
            {{--            @endif--}}
            {{--            @if(auth()->user()->isadmin)--}}
            {{--                <li class="nav-item">--}}
            {{--                    <a href="{{route('receptionist.sale.all')}}" aria-expanded="false" class="nav-link">--}}
            {{--                        <i class="fa-solid fa-money-bill-1"></i>--}}
            {{--                        <span class="ml-3 item-text">@lang("فواتير البيع")</span>--}}
            {{--                    </a>--}}
            {{--                </li>--}}
            {{--            @endif--}}
        </ul>


        @if(auth()->user()->isreceptionist)
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>@lang("الموظفين")</span>

            </p>
            <ul class="navbar-nav flex-fill w-100 mb-2">


                <li class="nav-item dropdown">
                    <a href="#employee" data-toggle="collapse" aria-expanded="false"
                       class="dropdown-toggle nav-link collapsed">
                        <i class="fa-solid fa-users"></i>
                        <span class="ml-3 item-text">@lang("الموظفين")</span>
                    </a>
                    <ul class="list-unstyled pl-4 w-100 collapse" id="employee" style="">

                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{route("receptionist.employee.show.all",1)}}"><span
                                    class="ml-1 item-text">@lang("المهندسين")</span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{route("receptionist.employee.show.all",2)}}"><span
                                    class="ml-1 item-text">@lang("موظفين الاستقبال")</span></a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link pl-3" href="{{route("receptionist.employee.show.all",3)}}"><span
                                    class="ml-1 item-text">@lang("موظفين المخازن")</span></a>
                        </li>

                    </ul>
                </li>


                <li class="nav-item">
                    <a href="{{route("bonus.all")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-money-bill-trend-up"></i>
                        <span class="ml-3 item-text">@lang("سجل البونص")</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{route("deduction.all")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-circle-minus"></i>
                        <span class="ml-3 item-text">@lang("سجل الخصم")</span>
                    </a>
                </li>

                @if(auth()->user()->isadmin)

                    <li class="nav-item">
                        <a href="{{route("salary.all",0)}}" aria-expanded="false" class="nav-link">
                            <i class="fa-solid fa-landmark"></i>
                            <span class="ml-3 item-text">@lang("طلبات الراتب")</span>
                            <span class="badge badge-pill badge-danger">{{SalaryRequestsCount()}}</span>

                        </a>
                    </li>




                    <li class="nav-item">
                        <a href="{{route("salary.all",1)}}" aria-expanded="false" class="nav-link">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <span class="ml-3 item-text">@lang("سجل الراتب")</span>
                        </a>
                    </li>
                @endif

            </ul>
        @endif


        @if(auth()->user()->isreceptionist)
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>@lang("الحضور و الانصراف")</span>

            </p>
            <ul class="navbar-nav flex-fill w-100 mb-2">


                <li class="nav-item">
                    <a href="{{route("receptionist.attendance.request.arrival")}}" aria-expanded="false"
                       class="nav-link">
                        <i class="fa-solid fa-check"></i>
                        <span class="ml-3 item-text">@lang("طلبات الحضور")</span>
                        <span class="badge badge-pill badge-danger">{{arrivalRequestsCount()}}</span>

                    </a>
                </li>


                <li class="nav-item">
                    <a href="{{route("receptionist.attendance.request.departure")}}" aria-expanded="false"
                       class="nav-link">
                        <i class="fa-solid fa-right-from-bracket"></i>
                        <span class="ml-3 item-text">@lang("طلبات الانصراف")</span>
                        <span class="badge badge-pill badge-danger">{{departureRequestsCount()}}</span>

                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route("receptionist.attendance.history")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span class="ml-3 item-text">@lang("سجل الحضور و الانصراف")</span>
                    </a>
                </li>
            </ul>
        @endif



        {{--        @if(auth()->user()->isadmin)--}}
        {{--            <p class="text-muted nav-heading mt-4 mb-1">--}}
        {{--                <span>@lang("العملاء")</span>--}}

        {{--            </p>--}}
        {{--            <ul class="navbar-nav flex-fill w-100 mb-2">--}}


        {{--                <li class="nav-item">--}}
        {{--                    <a href="{{route("receptionist.customer.all")}}" aria-expanded="false" class="nav-link">--}}
        {{--                        <i class="fa-solid fa-users"></i>--}}
        {{--                        <span class="ml-3 item-text">@lang("العملاء")</span>--}}
        {{--                    </a>--}}
        {{--                </li>--}}


        {{--            </ul>--}}
        {{--        @endif--}}

        @if(auth()->user()->isreceptionist)
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>@lang("عام")</span>
            </p>
            <ul class="navbar-nav flex-fill w-100 mb-2">

                <li class="nav-item">
                    <a href="{{route("receptionist.expense.all")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-file-invoice"></i>
                        <span class="ml-3 item-text">@lang("مصروفات عامة")</span>
                    </a>
                </li>

            </ul>
        @endif
        @if(auth()->user()->isreceptionist)
            <p class="text-muted nav-heading mt-4 mb-1">
                <span>@lang("الاعدادات")</span>
            </p>
            <ul class="navbar-nav flex-fill w-100 mb-2">

                <li class="nav-item">
                    <a href="{{route("user.setting")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-user-gear"></i>
                        <span class="ml-3 item-text">@lang("الاعدادات الشخصية")</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{route("receptionist.setting.general")}}" aria-expanded="false" class="nav-link">
                        <i class="fa-solid fa-gears"></i>
                        <span class="ml-3 item-text">@lang("الاعدادات العامة")</span>
                    </a>
                </li>

                {{--                <li class="nav-item">--}}
                {{--                    <a href="" aria-expanded="false" class="nav-link">--}}
                {{--                        <i class="fa-solid fa-floppy-disk"></i>--}}
                {{--                        <span class="ml-3 item-text">@lang("نسخ احتياطي")</span>--}}
                {{--                    </a>--}}
                {{--                </li>--}}

            </ul>
        @endif

    </nav>
</aside>
