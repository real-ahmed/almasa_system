@extends("layouts.app")

@section("panel")
    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline">
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
                        {{--                        <button data-toggle="modal" data-target="#varyModal" class="btn btn-primary float-right ml-3"--}}
                        {{--                                type="button">@lang("+ موظف جديد")</button>--}}
                    </div>
                </div>
            </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>@lang("رقم")</th>
                    <th>@lang("الاسم")</th>
                    <th>@lang("رقم الهاتف")</th>
                    <th>@lang("العنوان")</th>
                    <th>@lang("تاريخ الاضافة")</th>
                    <th>@lang("العمليات")</th>

                </tr>
                </thead>
                <tbody>
                @foreach($customers as $customer)
                    <tr>
                        <td>{{$customers->firstItem() + $loop->index}}</td>
                        <td><a href="{{route('receptionist.screen.receive.all')}}?search={{$customer->phone}}">{{$customer->name}}</a></td>
                        <td>{{$customer->phone}}</td>
                        <td>{{$customer->address}}</td>
                        <td>{{date_format($customer->created_at,'m/d/y h:iA')}}</td>

                        <td><a data-toggle="modal" data-target="#customerModal" data-id="{{$customer->id}}" data-name="{{$customer->name}}" data-address="{{$customer->address}}" data-phone="{{$customer->phone}}" class=" btn btn-primary
                               edit"><i
                                    class="fa-solid fa-pen"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <nav aria-label="Table Paging" class="mb-0 text-muted">
                @if ($customers->hasPages())
                    {{ $customers->links() }}
                @endif
            </nav>

        </div>
    </div>

    @include("customer.model")
@endsection
@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#customerModal');

            var saveAction = `{{ route('receptionist.customer.save') }}`;


            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=name]').val(data.name);
                modal.find('[name=phone]').val(data.phone);
                modal.find('[name=address]').val(data.address);


            });

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
            });


        })(jQuery);
    </script>
@endpush
