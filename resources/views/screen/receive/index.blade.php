@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('receptionist.screen.receive.all')}}">

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
                        <a href="{{route('receptionist.screen.receive.model')}}"
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

                        <th>@lang("العميل")</th>
                        <th>@lang("موظف الاستقبال")</th>
                        <th>@lang("تاريخ الاستلام")</th>
                        <th>@lang("تاريخ التسليم المتوقع")</th>


                        <th>@lang("العمليات")</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($repairs as $repair)
                        <tr>
                            <td>{{$repairs->firstItem() + $loop->index}}</td>
                            <td>{{$repair->reference_number}}</td>
                            <td>{{$repair->customer->name}}</td>
                            <td>{{$repair->receptionist->name}}</td>


                            <td>{{ \Carbon\Carbon::parse($repair->receive_date)->format('d/m/y h:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($repair->expected_delivery_date)->format('d/m/y h:i') }}</td>


                            <td>


                                <a href="{{route('receptionist.screen.receive.details',$repair->id)}}"
                                   class="btn btn-secondary"><i class="fa-solid fa-display"></i></a>




                                @if(auth()->user()->isadmin)
                                    <a href="{{route('receptionist.screen.receive.model',$repair->id)}}"
                                       class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $repair->id }}"
                                       class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                @endif
                            </td>


                        </tr>
                    @endforeach

                    </tbody>

                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($repairs->hasPages())
                        {{ $repairs->links() }}
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

            var deleteAction = `{{ route('admin.screen.receive.delete') }}`;

            var deleteModal = $('#deleteModel');

            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });



        })(jQuery);
    </script>
@endpush
