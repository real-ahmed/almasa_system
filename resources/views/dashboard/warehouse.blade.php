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
                                        <i class="fa-solid text-primary fa-2x fa-money-bill-trend-up"></i>

                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">@lang('بونص اليوم')</p>
                                        <span
                                            class="h3 mb-0 text-white">{{showAmount($cards['TodayBonus'])}} {{$general->money_sign}}</span>
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
                                        <i class="fa-solid text-primary fa-2x fa-circle-minus"></i>

                                    </div>
                                    <div class="col pr-0">
                                        <p class="small text-muted mb-0">@lang('خصم اليوم')</p>
                                        <span
                                            CLASS="h3 mb-0 text-white">{{showAmount($cards['TodayDeduction'])}} {{$general->money_sign}}</span>
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
                                        <i class="fa-solid text-primary fa-2x fa-landmark"></i>

                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">@lang('اجمالي الربح لليوم')</p>
                                        <div class="row align-items-center no-gutters">
                                            <div class="col-auto">
                                                <span
                                                    CLASS="h3 mb-0 text-white">{{showAmount($cards['TodayBonus']-$cards['TodayDeduction'])}} {{$general->money_sign}}</span>
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

                                        <i class="fa-solid text-primary fa-2x fa-boxes-packing"></i>

                                    </div>
                                    <div class="col">
                                        <p class="small text-muted mb-0">@lang('اجمالي عدد الاضناف')</p>
                                        <span class="h3 mb-0"> {{$cards['TotalProduct']}}</span>
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

                                        <span
                                            class="h2 mb-0">{{showAmount($cards['MonthlyBonus'])}} {{$general->money_sign}}</span>

                                        <p class="small text-muted mb-0">@lang('اجمالي بونص الشهر')</p>
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
                                        <span
                                            class="h2 mb-0">{{showAmount($cards['MonthlyDeduction'])}} {{$general->money_sign}}</span>
                                        <p class="small text-muted mb-0">@lang('اجمالي خصم الشهر')</p>
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
                                        <span class="h2 mb-0">{{$cards['RequestedItemsCount']}}</span>
                                        <p class="small text-muted mb-0">@lang('طلبات قطع الغيار')</p>
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
                                <h5 class="card-title">@lang('طلبات قطع الغيار')</h5>
                            </div>
                            <div class="col ml-auto">
                                <div class="dropdown float-right">
                                    <a href="{{route("item.request.all")}}"
                                       class="btn btn-primary float-right ml-3"
                                    >@lang("عرض الكل")</a>
                                </div>
                            </div>
                        </div>
                        <div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>@lang("اسم المنتج")</th>
                                    <th>@lang("الرقم التسلسلي")</th>
                                    <th>@lang("البراند")</th>
                                    <th>@lang("الفئة")</th>
                                    <th>@lang("الفئة الفرعية")</th>
                                    <th>@lang("الموظف")</th>
                                    <th>@lang("الكمية المطلوبة")</th>
                                    <th>@lang("الكمية المتوفرة")</th>


                                </tr>
                                </thead>
                                <tbody>
                                @forelse($components as $component)
                                    <tr>
                                        <td>{{$component->name}}</td>
                                        <td>{{$component->code}} </td>
                                        <td>{{$component->brand->name??'-'}}</td>
                                        <td>{{$component->category->name}}</td>
                                        <td>{{$component->subcategory->name ?? "-"}}</td>
                                        <td>{{$component->manualItemRequest->employee->name??'النظام'}}</td>

                                        <td>{{$component->RequestedQuantity}}</td>
                                        <td>{{$component->instockQuantity}}</td>

                                    <tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center"
                                            colspan="100%">@lang('لا توجد طلبات تسليم')</td>
                                    </tr>
                                @endforelse

                                </tbody>

                            </table>

                        </div>
                    </div>
                </div>
@endsection






