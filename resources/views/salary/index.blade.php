@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('salary.all')}}">

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
                    {{--                    <div class="dropdown float-right">--}}
                    {{--                        <button data-toggle="modal" data-target="#repairModal" class="btn btn-primary float-right ml-3"--}}
                    {{--                                type="button">@lang("+ نوع جديد")</button>--}}
                    {{--                    </div>--}}
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        @if(auth()->user()->isadmin)
                            <th>@lang("اسم الموظف")</th>
                        @endif
                        <th>@lang("الراتب الثابت")</th>
                        <th>@lang("البونص")</th>
                        <th>@lang("الخصم")</th>
                        <th>@lang("الاجمالي")</th>
                        <th>@lang("الحالة")</th>
                        <th>@lang("التاريخ")</th>
                        @if(auth()->user()->isadmin)
                            <td>@lang('العمليات')</td>

                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($salaryPayments as $payment)
                        <tr>
                            <td>{{$salaryPayments->firstItem() + $loop->index}}</td>
                            @if(auth()->user()->isadmin)
                                <td>{{$payment->user->name}}</td>
                            @endif
                            <td>{{showAmount($payment->salary_amount)}} {{$general->money_sign}}</td>
                            <td>{{showAmount($payment->bonuses_amount)}} {{$general->money_sign}}</td>
                            <td>{{showAmount($payment->deductions_amount)}} {{$general->money_sign}}</td>
                            <td>{{showAmount($payment->bonuses_amount + $payment->salary_amount - $payment->deductions_amount)}} {{$general->money_sign}}</td>
                            <td><?php echo $payment->statusname ?></td>

                            <td>{{date_format($payment->created_at,'m/d/y h:iA')}}</td>
                            @if(auth()->user()->isadmin)
                                <td>
                                    <a data-toggle="modal" data-target="#salaryModal"
                                       data-id="{{ $payment->id }}"
                                       data-salary_amount="{{ $payment->salary_amount }}"
                                       data-bonuses_amount="{{ $payment->bonuses_amount }}"
                                       data-status="{{ $payment->status }}"
                                       class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                </td>
                            @endif

                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($salaryPayments->hasPages())
                        {{ $salaryPayments->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @include("salary.model")

@endsection






@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#salaryModal');

            var saveAction = `{{ route('admin.salary.save') }}`;


            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=salary_amount]').val(data.salary_amount);
                modal.find('[name=bonuses_amount]').val(data.bonuses_amount);
                modal.find('[name=status]').val(data.status);
            });

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
            });


        })(jQuery);
    </script>
@endpush

