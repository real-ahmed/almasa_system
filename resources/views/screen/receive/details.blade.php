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
                    <iframe id="print" srcdoc="{{$printReceive}}" hidden></iframe>
                    <a id="print-codes-btn" class="btn btn-dark">@lang('طباعة اكواد الشاشات')</a>
                    <iframe id="print-codes" srcdoc="{{$printScreensCodes}}" hidden=""></iframe>
                    @if($repair->IsDone) <a class="btn btn-secondary" href="{{route('receptionist.repair.deliver.details',$repair->deliver->id)}}" >@lang('فاتورة التسليم')</a> @endif

                </div>
            </div>


            <div class="card-body">
                <strong class="card-title">@lang("معلومات العميل")</strong>
                <div class="row card-body">
                    <div class="col-md-6">
                        <p><strong>@lang("رقم الهاتف") : </strong>{{ $repair->customer->phone ?? '' }}</p>
                        <p><strong>@lang("العنوان") : </strong>{{ $repair->customer->address ?? '' }}</p>

                    </div>
                    <div class="col-md-6">
                        <p><strong>@lang("اسم العميل") : </strong>{{ $repair->customer->name ?? '' }}</p>
                    </div>
                </div>

                <strong class="card-title">@lang("معلومات الفاتورة")</strong>
                <div class="row card-body">
                    <div class="col-md-6">
                        <p><strong>@lang("تاريخ الاستلام") : </strong>{{ $repair->receive_date ?? '' }}</p>
                        <p><strong>@lang("موظف الاستقبال") : </strong>{{ $repair->receptionist->name ?? '' }}</p>
                        <p>                    <strong>@lang('عربون :')</strong>
                            <span
                                id="totalAmount">{{$repair->paid  ?showAmount($repair->paid ): '0'}} </span> {{$general->money_sign}}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>@lang("تاريخ التسليم المتوقع") : </strong>{{ $repair->expected_delivery_date ?? '' }}
                        </p>
                        <p><strong>@lang("الرقم المرجعي") : </strong>{{ $repair->reference_number ?? '' }}</p>

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
                        <th>@lang("المشكلة")</th>

                        <th>@lang("المخزن")</th>
                        <th>@lang('الحالة')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($repair->screens as $screen)
                        <tr>
                            <td>{{ $screen->code }}</td>
                            <td>{{ $screen->brand->name }}</td>
                            <td>{{ $screen->model }}</td>
                            <td>{{ $screen->serial }}</td>

                            <td>{{ $screen->engineer_receive->name }}</td>
                            <td>{{ $screen->issue }}</td>
                            <td>{{ $screen->warehouse->name }}</td>
                            <td><?php echo $screen->statusName ?></td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
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
