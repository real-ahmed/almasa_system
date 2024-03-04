@extends("layouts.app")

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("معلومات الشراء")</strong>
            </div>
            <div class="card-body">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>@lang('البراند') : </strong>{{$screen->brand->name}}
                        </div>

                        <div class="mb-3">
                            <strong>@lang('العميل') : </strong>{{$screen->repairs->first()->customer->name}}
                        </div>

                        <div class="mb-3">
                            <strong>@lang('المخزن') : </strong>{{$screen->warehouse->name}}
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>@lang('الموديل') : </strong>{{$screen->model}}
                        </div>
                        <div class="mb-3">
                            <strong>@lang('الرسبشن') : </strong>{{$screen->repairs->first()->receptionist->name}}
                        </div>


                    </div>
                </div>


                <div class="card-body">

                    <table class="table table-hover" id="purchaseItemsTable">
                        <thead>
                        <tr>
                            <th>@lang("المنتج/الخدمة")</th>
                            <th>@lang("الكمية")</th>
                            <th>@lang("السعر")</th>
                            <th>@lang("الإجمالي")</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($screen))
                            @foreach($screen->components as $component)
                                <tr>

                                    <td>
                                        {{$component->component->name}}
                                    </td>
                                    <td>
                                        <span>{{$component->quantity}}</span>
                                    </td>
                                    <td>
                                        <span class="price">{{$component->price}}</span>
                                    </td>
                                    <td><span
                                            class="total-price">{{$component->price * $component->quantity}}</span> {{$general->money_sign}}
                                    </td>

                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>


                </div>


                <div class="card-body">

                    <table class="table table-hover" id="serviceItemsTable">
                        <thead>
                        <tr>
                            <th>@lang("المنتج/الخدمة")</th>
                            <th>@lang("السعر")</th>
                            <th>@lang("الإجمالي")</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($screen))
                            @foreach($screen->services as $service)
                                <tr>


                                    <td>

                                        {{$service->service->name}}
                                    </td>
                                    <td>
                                        <span class="price">{{$service->price}}</span> {{$general->money_sign}}
                                    </td>
                                    <td><span class="total-price">{{$service->price}}</span> {{$general->money_sign}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                    <!-- Total Amount -->
                    <div>
                        <strong>@lang('اجمالي الفاتورة :')</strong>
                        <span id="totalAmount">{{$screen->repair_amount  ?? '0'}} </span> {{$general->money_sign}}
                    </div>

                </div>


            </div>
        </div>
    </div>
@endsection

