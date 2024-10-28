@extends('layouts.app')

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <strong class="card-title">@lang("وصل تسليم")</strong>
                </div>
                <div>
                    <iframe id="print" srcdoc="{{$printDeliver}}" hidden></iframe>
                    <a id="print-btn"  class="btn btn-secondary">@lang('طباعة الوصل')</a>

                    @if(!$deliver->isPaid)
                        <a data-toggle="modal" data-target="#paidModal" class="btn btn-primary">@lang('تحصيل دفعة')</a>

                    @endif


                    <a data-toggle="modal" data-target="#noteModel" class="btn btn-danger">@lang('ملاحظة')</a>


                    @if($deliver->status ==0)
                        <a href="{{route('receptionist.repair.deliver.save',$deliver->id)}}"
                           class="btn btn-success">@lang('تم التسليم')</a>

                    @endif

                </div>
            </div>


            <div class="card-body">
                <strong class="card-title">@lang("معلومات العميل")</strong>
                <div class="row card-body">
                    <div class="col-md-6">
                        <p><strong>@lang("رقم الهاتف") : </strong>{{ $deliver->repair->customer->phone ?? '' }}</p>
                        <p><strong>@lang("العنوان") : </strong>{{ $deliver->repair->customer->address ?? '' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>@lang("اسم العميل") : </strong>{{ $deliver->repair->customer->name ?? '' }}</p>
                    </div>
                </div>

                <strong class="card-title">@lang("معلومات الفاتورة")</strong>
                <div class="row card-body">
                    <div class="col-md-6">
                        <p><strong>@lang("موظف الاستقبال") : </strong>{{ $deliver->repair->receptionist->name ?? '' }}
                        </p>
                        <p><strong>@lang("تاريخ التسليم المتوقع")
                                : </strong>{{ $deliver->repair->expected_delivery_date ?? '' }}</p>
                        <p><strong>@lang("رقم الاستلام المرجعي ")
                                : </strong>{{ $deliver->repair->reference_number ?? '' }}</p>
                        <p><strong>@lang("ملاحظة")
                                : </strong>{{ $deliver->note ?? '' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>@lang("تاريخ الاستلام") : </strong>{{ $deliver->repair->receive_date ?? '' }}</p>
                        <p><strong>@lang("تاريخ الوصل") : </strong>{{ $deliver->repair->created_at ?? '' }}</p>
                        <p><strong>@lang("الرقم المرجعي") : </strong>{{ $deliver->reference_number ?? '' }}</p>
                        <p><strong>@lang("تاريخ التسليم (طباعة الفاتورة)") : </strong>{{ $deliver->invoice_print_date ?? '' }}</p>

                    </div>
                </div>
                <strong class="card-title">@lang("الشاشات")</strong>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("الكود")</th>
                        <th>@lang("الماركة")</th>
                        <th>@lang("الموديل")</th>
                                                <th>@lang("السريال")</th>

                        <th>@lang("مهندس الإستلام")</th>
                        <th>@lang("المخزن")</th>
                        <th>@lang("الحالة")</th>
                        <th>@lang('التكلفة')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deliver->repair->screens->whereNotIn('status',[4, 5]) as $screen)
                        <tr>
                            <td>{{ $screen->code }}</td>
                            <td>{{ $screen->brand->name ?? null }}</td>
                            <td>{{ $screen->model }}</td>
                                                        <td>{{ $screen->serial }}</td>

                            <td>{{ $screen->engineer_receive->name }}</td>
                            <td>{{ $screen->warehouse->name }}</td>
                            <td>@php(print ($screen->statusname))</td>
                            <td>{{showAmount($screen->repair_amount) }} {{$general->money_sign}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Total Amount -->
                <div>
                    <strong>@lang('اجمالي الفاتورة :')</strong>
                    <span
                        id="totalAmount">{{$deliver->total_amount  ?showAmount($deliver->total_amount ): '0'}} </span> {{$general->money_sign}}
                </div>
                <div>
                    <strong>@lang('عربون :')</strong>
                    <span
                        id="totalAmount">{{$deliver->repair->paid  ?showAmount($deliver->repair->paid ): '0'}} </span> {{$general->money_sign}}
                </div>
                <div>
                    <strong>@lang('تم تحصيل :')</strong>
                    <span
                        id="totalAmount">{{$deliver->received_amount  ?showAmount($deliver->received_amount ): '0'}} </span> {{$general->money_sign}}
                </div>
                <br>
                <div>
                    <strong>@lang('المستحق :')</strong>
                    <span
                        id="totalAmount">{{$deliver->total_amount  ?showAmount($deliver->total_amount -( $deliver->received_amount+$deliver->repair->paid ) ): '0'}} </span> {{$general->money_sign}}
                </div>
            </div>
        </div>
    </div>


    @if(!$deliver->isPaid)
        @include("deliver.model")
    @endif
    @include("deliver.note")

@endsection


@push('script')
    <script>

        (function ($) {
            "use strict";
            $('#print-btn').click( function () {
                $('#print')[0].contentWindow.print();
                $.ajax({
                    url: "{{route('receptionist.repair.deliver.print',$deliver->id)}}" ,
                    method: 'GET',
                    success: function (response) {

                    },
                    error: function (error) {
                        console.log(error)
                    }
                });
            });

        })(jQuery);
    </script>
@endpush

