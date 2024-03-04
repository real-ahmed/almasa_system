@extends("layouts.app")

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <strong class="card-title">@lang("معلومات البيع")</strong>
                </div>

                <div>
                    <a id="print-btn" class="btn btn-primary">@lang('طباعة الوصل')</a>
                    <iframe id="print" srcdoc="{{$printInvoice}}" hidden></iframe>
                </div>
            </div>
            <div class="card-body">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>@lang('الرقم المرجعي') : </strong>{{$invoice->reference_number}}
                        </div>

                        <div class="mb-3">
                            <strong>@lang('العميل') : </strong>{{$invoice->customer->name}}
                        </div>


                    </div>
                    <div class="col-md-6">

                        <div class="mb-3">
                            <strong>@lang('موظف الاستقبال') : </strong>{{$invoice->receptionist->name}}
                        </div>
                        <div class="mb-3">
                            <strong>@lang('التاريخ') : </strong> {{date_format($invoice->created_at,'m/d/y h:iA')}}
                        </div>


                    </div>
                </div>


                <div class="card-body">

                    <table class="table table-hover" id="purchaseItemsTable">
                        <thead>
                        <tr>
                            <th>@lang("المنتج")</th>
                            <th>@lang("الكمية")</th>
                            <th>@lang("السعر")</th>
                            <th>@lang("الإجمالي")</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if(isset($invoice))
                            @foreach($invoice->components as $component)
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

                    <!-- Total Amount -->
                    <div>
                        <strong>@lang('اجمالي الفاتورة :')</strong>
                        <span id="totalAmount">{{$invoice->total_amount  ?? '0'}} </span> {{$general->money_sign}}
                    </div>
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


        })(jQuery);
    </script>
@endpush
