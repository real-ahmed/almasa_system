@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('warehouse.employee.purchase.all')}}">

                        <div class="form-row">
                            <div class="input-group col-auto">
                                <input type="text" class="form-control" value="{{request()->input('search')}}"
                                       name="search" placeholder="@lang("بحث")" aria-describedby="button-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                            class="fa-solid fa-magnifying-glass"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col ml-auto">
                    <div class="dropdown float-right">
                        <a href="{{route('warehouse.employee.purchase.model')}}"
                           class="btn btn-primary float-right ml-3"
                           type="button">@lang("+ فاتورة جديدة")</a>
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الرقم المرجعي")</th>

                        <th>@lang("التاجر")</th>
                        <th>@lang("موظف المخزن")</th>
                        <th>@lang("المخزن")</th>
                        <th>@lang("اجمالي الفاتورة")</th>
                        <th>@lang("التاريخ")</th>
                        <th>@lang("العمليات")</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($purchases as $purchase)
                        <tr>
                            <td>{{$purchases->firstItem() + $loop->index}}</td>
                            <td>{{$purchase->reference_number}}</td>
                            <td>{{$purchase->supplier->name}}</td>
                            <td>{{$purchase->warehouseEmployee->name}}</td>
                            <td>{{$purchase->warehouse->name}}</td>
                            <td>{{$purchase->total_amount}} {{$general->money_sign}}</td>

                            <td>{{date_format($purchase->created_at,'m/d/y  h:iA')}}</td>


                            <td>

                                <a data-id="{{ $purchase->id }}"
                                   data-reference_number="{{ $purchase->reference_number }}"
                                   class="btn btn-secondary view-invoice"><i class="fa-solid fa-display"></i></a>

                                @if(auth()->user()->iswarehouseemployee)

                                    <a href="{{route('warehouse.employee.purchase.model',$purchase->id)}}"
                                       class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>

                                @if(auth()->user()->isadmin)


                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $purchase->id }}"
                                       class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                @endif
                                                                @endif

                            </td>


                        </tr>
                    @endforeach

                    </tbody>

                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($purchases->hasPages())
                        {{ $purchases->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    @include("purchase.invoiceModel")

    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";

            var deleteAction = `{{ route('warehouse.employee.purchase.delete') }}`;
            var invoiceAction = `{{ route('warehouse.employee.purchase.invoice') }}`;
            var printAction = `{{ route('warehouse.employee.purchase.print') }}`;


            var deleteModal = $('#deleteModel');
            var invoiceModal = $('#invoiceModal');

            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });

            $('.view-invoice').click(function () {
                var data = new $(this).data();
                fetchInvoiceDetails(data.id, data.reference_number);
            });


            function fetchInvoiceDetails(id, reference_number) {
                $.ajax({
                    url: `${invoiceAction}/${id}`,
                    method: 'GET',
                    success: function (data) {
                        invoiceModal.find('.modal-title').text(reference_number);
                        invoiceModal.find('.modal-body').html(data);
                        invoiceModal.modal('show');
                    },
                    error: function () {
                        console.error('Error fetching invoice details');
                    }
                });

                $.ajax({
                    url: `${printAction}/${id}`,
                    type: 'GET',
                    success: function (response) {
                        var htmlContent = response.content;

                        // Set the content of the modal body to an iframe with the generated HTML
                        invoiceModal.find('.print').html('<iframe hidden="" ></iframe>');


                        var iframe = $('#invoiceModal iframe')[0];
                        var blob = new Blob([htmlContent], {type: 'text/html'});
                        iframe.src = URL.createObjectURL(blob);

                        iframe.onload = function () {
                            $('#invoiceModal').on('click', '.btn-print', function () {
                                // Check if iframe is not null before trying to print
                                if (iframe && iframe.contentWindow) {
                                    iframe.contentWindow.print();
                                } else {
                                    console.error('Error accessing iframe or contentWindow.');
                                }
                            });
                        };

                    }
                });


            }

        })(jQuery);
    </script>
@endpush
