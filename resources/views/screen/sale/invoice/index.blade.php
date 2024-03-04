@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('receptionist.sale.screen.all')}}">

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
                        <a href="{{route('receptionist.sale.screen.model')}}" class="btn btn-primary float-right ml-3"
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

                        <th>@lang("العميل")</th>
                        <th>@lang("موظف الاستقبال")</th>
                        <th>@lang("اجمالي الفاتورة")</th>
                        <th>@lang("التاريخ")</th>
                        <th>@lang("العمليات")</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($invoices as $invoice)
                        <tr>
                            <td>{{$invoices->firstItem() + $loop->index}}</td>
                            <td>{{$invoice->reference_number}}</td>
                            <td>{{$invoice->customer->name}}</td>
                            <td>{{$invoice->receptionist->name}}</td>
                            <td>{{$invoice->total_amount}} {{$general->money_sign}}</td>

                            <td>{{date_format($invoice->created_at,'d/m/y h:i')}}</td>


                            <td>

                                <a href="{{route('receptionist.sale.screen.details',$invoice->id)}}"
                                   class="btn btn-secondary "><i class="fa-solid fa-display"></i></a>


                                <a href="{{route('receptionist.sale.screen.model',$invoice->id)}}"
                                   class="btn btn-primary edit"><i
                                        class="fa-solid fa-pen"></i></a>


                                <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $invoice->id }}"
                                   class="btn btn-danger delete"><i
                                        class="fa-solid fa-trash"></i></a>
                            </td>


                        </tr>
                    @endforeach

                    </tbody>

                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($invoices->hasPages())
                        {{ $invoices->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>


    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {
            "use strict";

            var deleteAction = `{{ route('receptionist.sale.screen.delete') }}`;


            var deleteModal = $('#deleteModel');


            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });


        })(jQuery);
    </script>
@endpush
