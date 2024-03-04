@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('admin.repair.type.all')}}">

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
                        <button data-toggle="modal" data-target="#repairModal" class="btn btn-primary float-right ml-3"
                                type="button">@lang("+ نوع جديد")</button>
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الاسم")</th>
                        <th>@lang("البونص")</th>
                        <th>@lang("سعر البيع")</th>
                        <th>@lang("العمليات")</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($types as $type)
                        <tr>
                            <td>{{$types->firstItem() + $loop->index}}</td>
                            <td>{{$type->name}}</td>
                            <td>{{$type->default_bonus}} {{$general->money_sign}}</td>
                            <td>{{$type->price}} {{$general->money_sign}}</td>
                            <td><a data-toggle="modal" data-target="#repairModal" data-id="{{ $type->id }}"
                                   data-name="{{ $type->name }}"
                                   data-default_bonus="{{ showAmount($type->default_bonus) }}"
                                   data-price="{{ showAmount($type->price) }}" data-bonus_type="{{ $type->bonus_type }}"
                                   class="btn btn-primary edit"><i class="fa-solid fa-pen"></i></a></td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($types->hasPages())
                        {{ $types->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    @include("repairtypes.model")

@endsection


@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#repairModal');

            var action = `{{ route('admin.repair.type.save') }}`;


            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${action}/${data.id}`);
                modal.find('[name=name]').val(data.name);
                modal.find('[name=default_bonus]').val(data.default_bonus);
                modal.find('[name=bonus_type]').val(data.bonus_type);
                modal.find('[name=price]').val(data.price);


            });

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();

            });


        })(jQuery);
    </script>
@endpush
