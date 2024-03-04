@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('receptionist.user.advance.payment.all',['employeeId' => request()->route('employeeId')])}}">

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
                                            <button data-toggle="modal" data-target="#paymentModal" class="btn btn-primary float-right ml-3"
                                                    type="button">@lang("+ سلفة جديد")</button>
                                        </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        @if(auth()->user()->isreceptionist)
                            <th>@lang("اسم الموظف")</th>
                        @endif
                        <th>@lang("المبلغ")</th>
                        <th>@lang("السبب")</th>

                        <th>@lang("التاريخ")</th>
                        @if(auth()->user()->isreceptionist)
                            <td>@lang('العمليات')</td>

                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>{{$payments->firstItem() + $loop->index}}</td>
                            @if(auth()->user()->isreceptionist)
                                <td>{{$payment->employee->name}}</td>
                            @endif
                            <td>{{showAmount($payment->amount)}} {{$general->money_sign}}</td>
                            <td>{{$payment->note ?? '-'}}</td>
                            <td>{{date_format($payment->created_at,'m/d/y h:iA')}}</td>
                            @if(auth()->user()->isreceptionist)

                                <td>
                                    <a data-toggle="modal" data-target="#paymentModal"
                                       data-id="{{ $payment->id }}"
                                       data-amount="{{$payment->amount}}"
                                       data-note="{{$payment->note}}"
                                       class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $payment->id }}"
                                       class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                </td>
                            @endif

                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($payments->hasPages())
                        {{ $payments->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @include("models.delete")
    @include("advance_payment.model")

@endsection






@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#paymentModal');

            var saveAction = `{{ route('receptionist.user.advance.payment.save') }}`;

            var deleteAction = `{{ route('receptionist.user.advance.payment.delete') }}`;


            var deleteModal = $('#deleteModel');
            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });



            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=amount]').val(data.amount);
                modal.find('[name=note]').val(data.note);


            });

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();

            });


        })(jQuery);
    </script>
@endpush
