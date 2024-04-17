@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('bonus.all')}}">

                        <div class="form-row">
                            <div class="form-group mb-3">
                                <label for="from_date" class="col-form-label">@lang("من")</label>

                                <div class="input-group">
                                    <input type="text" class="form-control drgpicker" id="date-input1"
                                           name="from_date"
                                           value="{{request()->input('to_date') ?date('m/d/Y',strtotime(request()->input('from_date'))): date('m/d/Y')}}"
                                           aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fa-solid fa-calendar-days"></span></div>
                                    </div>
                                </div>


                            </div>


                            <div class="form-group mb-3">
                                <label for="from_date" class="col-form-label">@lang("الى")</label>

                                <div class="input-group">
                                    <input type="text" class="form-control drgpicker" id="date-input1"
                                           name="to_date"
                                           value="{{request()->input('to_date')?date('m/d/Y', strtotime(request()->input('to_date'))): date('m/d/Y')}}"
                                           aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fa-solid fa-calendar-days"></span></div>
                                    </div>
                                </div>


                            </div>
                            <div class="form-group mb-3">

                                <div class="input-group col-auto">
                                    <input type="text" class="form-control" value="{{request()->input('search')}}"
                                           name="search" placeholder="@lang("بحث")" aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit" id="button-addon2"><i
                                                class="fa-solid fa-magnifying-glass"></i></button>
                                    </div>
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
                        <th>@lang("الشاشة")</th>

                        <th>@lang("التاريخ")</th>
                        @if(auth()->user()->isreceptionist)
                            <td>@lang('العمليات')</td>

                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bonuses as $bonus)
                        <tr>
                            <td>{{$bonuses->firstItem() + $loop->index}}</td>
                            @if(auth()->user()->isreceptionist)
                                <td>{{$bonus->employee->name}}</td>
                            @endif
                            <td>{{$bonus->amount}}</td>
                            <td>{{$bonus->screenBonus->screen->code ? $bonus->screenBonus->screen->brand->name ."-". $bonus->screenBonus->screen->repairs->first()->customer->name : '-'}}</td>
                            <td>{{date_format($bonus->created_at,'m/d/y h:iA')}}</td>
                            @if(auth()->user()->isreceptionist)

                                <td>
                                    <a data-toggle="modal" data-target="#bonusModal" data-id="{{ $bonus->id }}"
                                       data-amount="{{$bonus->amount}}"
                                       data-employee_id="{{$bonus->employee_id}}"
                                       class="btn btn-primary edit-bonus"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $bonus->id }}"
                                       class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                </td>
                            @endif

                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($bonuses->hasPages())
                        {{ $bonuses->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @include("models.bonus")
    @include("models.delete")

@endsection






@push('script')
    <script>
        (function ($) {

            "use strict";


            var deleteAction = `{{ route('receptionist.bonus.delete') }}`;


            var deleteModal = $('#deleteModel');
            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });


        })(jQuery);
    </script>
@endpush
