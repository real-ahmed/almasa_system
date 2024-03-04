@extends("layouts.app")

@section('panel')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow  ">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <i class="fa-solid text-primary fa-2x fa-gears"></i>

                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">@lang('ارباح الصيانة اليومية')</p>
                                        <span
                                            class="h3 mb-0 text-white">{{showAmount($cards['RepairTotalDailySales'])}} {{$general->money_sign}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <i class="fa-solid text-primary fa-2x fa-box"></i>

                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">@lang('ارباح قطع الغيار اليومية')</p>
                                        <span class="h3 mb-0">{{showAmount($cards['ProductTotalDailySales'])}} {{$general->money_sign}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <i class="fa-solid text-primary fa-2x fa-box-open"></i>

                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">@lang('عدد المتجات المباعة(يومي)')</p>
                                        <div class="row align-items-center no-gutters">
                                            <div class="col-auto">
                                                <span class="h3 mr-2 mb-0"> {{$cards['ProductTotalDailyCount']}} </span>
                                            </div>
                                            <div class="col-md-12 col-lg">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-3 text-center">
                                        <i class="fa-solid text-primary fa-2x fa-bell"></i>

                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">@lang('عدد طلبات الصيانة(يومي)')</p>
                                        <span class="h3 mb-0">{{$cards['RepairTotalDailyCount']}} </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end section -->
                <!-- info small box -->
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="h2 mb-0">{{showAmount($cards['RepairTotalMonthlySales'])}} {{$general->money_sign}}</span>

                                        <p class="small text-muted mb-0">@lang('ارباح الصيانة الشهرية')</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="fe fe-32 fe-shopping-bag text-muted mb-0"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="h2 mb-0">{{showAmount($cards['ProductTotalMonthlySales'])}} {{$general->money_sign}}</span>
                                        <p class="small text-muted mb-0">@lang('ارباح قطع الغيار الشهرية')</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="fe fe-32 fe-clipboard text-muted mb-0"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col">
                                        <span class="h2 mb-0">{{$cards['TotalCustomers']}}</span>
                                        <p class="small text-muted mb-0">@lang('اجمالي عدد العملاء')</p>
                                    </div>
                                    <div class="col-auto">
                                        <span class="fe fe-32 fe-users text-muted mb-0"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- end section -->


                <div class="card shadow">
                    <div class="card-body">
                        <div class="toolbar row mb-3">
                            <div class="col">
                                <h5 class="card-title">@lang('طلبات الرواتب')</h5>
                            </div>
                            <div class="col ml-auto">
                                <div class="dropdown float-right">
                                    <a href="{{route("salary.all",0)}}" class="btn btn-primary float-right ml-3"
                                    >@lang("عرض الكل")</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>@lang("اسم الموظف")</th>
                                    <th>@lang("الراتب الثابت")</th>
                                    <th>@lang("البونص")</th>
                                    <th>@lang("الخصم")</th>
                                    <th>@lang("الاجمالي")</th>
                                    <th>@lang("التاريخ")</th>


                                </tr>
                                </thead>
                                <tbody>
                                @forelse($salaryPayments as $payment)
                                    <tr>
                                        <td>{{$payment->user->name}}</td>
                                        <td>{{showAmount($payment->salary_amount)}} {{$general->money_sign}}</td>
                                        <td>{{showAmount($payment->bonuses_amount)}} {{$general->money_sign}}</td>
                                        <td>{{showAmount($payment->deductions_amount)}} {{$general->money_sign}}</td>
                                        <td>{{showAmount($payment->bonuses_amount + $payment->salary_amount - $payment->deductions_amount)}} {{$general->money_sign}}</td>
                                        <td>{{$payment->created_at}}</td>


                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center"
                                            colspan="100%">@lang('لا توجد طلبات قبض')</td>
                                    </tr>
                                @endforelse


                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

@endsection






