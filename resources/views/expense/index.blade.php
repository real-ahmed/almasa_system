@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('receptionist.expense.all')}}">

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
                        <button data-toggle="modal" data-target="#expenseModal" class="btn btn-primary float-right ml-3"
                                type="button">@lang("+ مصروف جديد")</button>
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الاسم")</th>
                        <th>@lang("المبلغ")</th>
                        <th>@lang("موظف الاستقبال")</th>
                        <th>@lang("الموظف")</th>
                        <th>@lang("القسم")</th>
                        <th>@lang("ملاحظة")</th>
                        <th>@lang("التاريخ")</th>
                        @if(auth()->user()->isAdmin)
                            <th>@lang("العمليات")</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($expenses as $expense)
                        <tr>
                            <td>{{$expenses->firstItem() + $loop->index}}</td>
                            <td>{{$expense->name}}</td>
                            <td>{{showAmount($expense->amount)}} {{$general->money_sign}}</td>
                            <td>{{$expense->receptionist->name}}</td>
                            <td>{{$expense->employee->name}}</td>
                            <td>{{$expense->departmentName}}</td>
                            <td>{{$expense->note}}</td>

                            <td>{{date_format($expense->created_at,'m/d/y h:iA')}}</td>

                            @if(auth()->user()->isAdmin)
                                <td>
                                    <a data-toggle="modal" data-target="#expenseModal" data-id="{{ $expense->id }}"
                                       data-name="{{ $expense->name }}"
                                       data-amount="{{ $expense->amount }}"
                                       data-note="{{ $expense->note }}"
                                       data-receptionist_id="{{ $expense->receptionist->id }}"
                                       data-employee_id="{{ $expense->employee_id }}"
                                       data-department="{{ $expense->department }}"
                                       class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $expense->id }}"
                                       data-name="{{ $expense->name }}" class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                </td>
                            @endif

                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($expenses->hasPages())
                        {{ $expenses->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    @include("expense.model")
    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#expenseModal');

            var saveAction = `{{ route('receptionist.expense.save') }}`;
            var deleteAction = `{{ route('receptionist.expense.delete') }}`;


            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=name]').val(data.name);
                modal.find('[name=amount]').val(data.amount);
                modal.find('[name=note]').val(data.note);
                modal.find('[name=receptionist_id]').val(data.receptionist_id).trigger('change');
                modal.find('[name=employee_id]').val(data.employee_id).trigger('change');
                modal.find('[name=department]').val(data.department);
            });

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
            });


            var deleteModal = $('#deleteModel');
            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });


        })(jQuery);
    </script>
@endpush
