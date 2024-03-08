@extends('layouts.app')

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <strong class="card-title">@lang("وصل استلام")</strong>
                </div>
                <div>
                    <a id="print-btn" class="btn btn-primary">@lang('طباعة الوصل')</a>
                    <iframe id="print" srcdoc="{{$printInvoice}}" hidden></iframe>

                </div>
            </div>


            <div class="card-body">
                <strong class="card-title">@lang("معلومات العميل")</strong>
                <div class="row card-body">
                    <div class="col-md-6">
                        <p><strong>@lang("رقم الهاتف") : </strong>{{ $invoice->customer->phone ?? '' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>@lang("اسم العميل") : </strong>{{ $invoice->customer->name ?? '' }}</p>
                    </div>
                </div>

                <strong class="card-title">@lang("معلومات الفاتورة")</strong>
                <div class="row card-body">
                    <div class="col-md-6">
                        <p><strong>@lang("تاريخ البيع") : </strong> {{date_format($invoice->created_at,'m/d/y h:iA')}}
                        </p>
                        <p><strong>@lang("الرقم المرجعي") : </strong>{{ $invoice->reference_number ?? '' }}</p>
                    </div>
                </div>
                <strong class="card-title">@lang("الشاشات")</strong>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("الكود")</th>
                        <th>@lang("الماركة")</th>
                        <th>@lang("الموديل")</th>
                        <th>@lang("المخزن")</th>
                        <th>@lang("سعر الشراء")</th>
                        <th>@lang("سعر البيع")</th>
                        <th>@lang("الحالة")</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->screens as $screen)
                        <tr>
                            <td>{{ $screen->screen->code }}</td>
                            <td>{{ $screen->screen->brand->name ?? null }}</td>
                            <td>{{ $screen->screen->model }}</td>
                            <td>{{ $screen->screen->warehouse->name }}</td>
                            <td>{{ showAmount($screen->screen->buy->price) }} {{$general->money_sign}}</td>
                            <td>{{ showAmount($screen->screen->sale->price) }} {{$general->money_sign}}</td>
                            <td><?php echo $screen->screen->statusName ?></td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <!-- Total Amount -->
                <div>
                    <strong>@lang('اجمالي الفاتورة :')</strong>
                    <span
                        id="totalAmount">{{$invoice->total_amount  ?showAmount($invoice->total_amount ): '0'}} </span> {{$general->money_sign}}
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";
            $('#print-btn').click(function () {
                $('#print')[0].contentWindow.print();
            });

            $('#print-codes-btn').click(function () {
                $('#print-codes')[0].contentWindow.print();
            });

        })(jQuery);
    </script>
@endpush
