@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('bonus.all')}}">

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
                        <th>@lang("اسم الموظف")</th>

                        <th>@lang("وقت الحضور")</th>
                        <th>@lang("وقت الانصراف")</th>
                        <th>@lang("الحالة")</th>
                        <th>@lang("الخصم")</th>

                        <th>@lang("التاريخ")</th>
                        <td>@lang('العمليات')</td>


                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $record)
                        <tr>
                            <td>{{$records->firstItem() + $loop->index}}</td>
                            <td>{{$record->employee->name}}</td>
                            <td>{{ $record->arrival_time ? \Carbon\Carbon::parse($record->arrival_time)->format('h:i A') : '-' }}</td>
                            <td>{{ $record->departure_time ? \Carbon\Carbon::parse($record->departure_time)->format('h:i A') : '-' }}</td>
                            <td>{!! $record->StatusName !!}</td>
                            <td>{{isset($record->deduction->amount) ? showAmount($record->deduction->amount) : '-'}} {{isset($record->deduction->amount)? $general->money_sign:''}}</td>
                            <td>{{date_format($record->created_at,'m/d/y h:iA')}}</td>


                            <td>
                                @if($record->status == 0)
                                    <a data-toggle="modal" data-target="#repairModal" data-id="{{ $record->id }}"
                                       class="btn btn-success edit"><i class="fa-solid fa-check"></i></a>
                                @elseif($record->status == 1)
                                    <a data-toggle="modal" data-target="#repairModal" data-id="{{ $record->id }}"
                                       class="btn btn-danger edit"><i class="fa-solid fa-right-from-bracket"></i></a>
                                @else
                                    <a data-toggle="modal" data-target="#repairModal" data-id="{{ $record->id }}"
                                       data-departure_time="{{ \Carbon\Carbon::parse($record->departure_time)->format('h:i A')}}"
                                       data-arrival_time="{{ \Carbon\Carbon::parse($record->arrival_time)->format('h:i A')}}"
                                       class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                @endif

                            </td>


                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($records->hasPages())
                        {{ $records->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @include("attendance.model")
    {{--    @include("models.delete")--}}

@endsection






@push('script')
    <script>
        (function ($) {

            "use strict";
            $('.timepicker').timepicker({
                timeFormat: 'h:mm p',
                interval: 60,
                minTime: '00:00am',
                maxTime: '11:00pm',
                defaultTime: '08:00am',
                startTime: '00:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            var saveAction = `{{ route('receptionist.attendance.save') }}`;


            var modal = $('#repairModal');
            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);



                modal.find('#departure_time').timepicker('setTime', data.departure_time);
                modal.find('#arrival_time').timepicker('setTime', data.arrival_time);

            });


        })(jQuery);
    </script>
@endpush

