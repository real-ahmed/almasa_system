@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('deduction.all')}}">

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
                        @if(auth()->user()->isreceptionist)
                            <th>@lang("اسم الموظف")</th>
                        @endif
                        <th>@lang("المبلغ")</th>

                        <th>@lang("التاريخ")</th>
                        @if(auth()->user()->isreceptionist)
                            <td>@lang('العمليات')</td>

                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($deductions as $deduction)
                        <tr>
                            <td>{{$deductions->firstItem() + $loop->index}}</td>
                            @if(auth()->user()->isreceptionist)
                                <td>{{$deduction->employee->name}}</td>
                            @endif
                            <td>{{$deduction->amount}}</td>
                            <td>{{date_format($deduction->created_at,'m/d/y h:iA')}}</td>

                            @if(auth()->user()->isreceptionist)

                                <td>
                                    <a data-toggle="modal" data-target="#deductionModal" data-id="{{ $deduction->id }}"
                                       data-amount="{{$deduction->amount}}"
                                       data-employee_id="{{$deduction->employee_id}}"
                                       class="btn btn-primary edit-deduction"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $deduction->id }}"
                                       class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                </td>
                            @endif

                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($deductions->hasPages())
                        {{ $deductions->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @include("models.deduction")
    @include("models.delete")

@endsection






@push('script')
    <script>
        (function ($) {

            "use strict";


            var deleteAction = `{{ route('receptionist.deduction.delete') }}`;


            var deleteModal = $('#deleteModel');
            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });


        })(jQuery);
    </script>
@endpush
