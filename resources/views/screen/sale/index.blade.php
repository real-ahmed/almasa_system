@extends("layouts.app")
@section('panel')
    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('receptionist.screen.sale.all')}}">

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
                        {{--                        <button data-toggle="modal" data-target="#componentModal"--}}
                        {{--                                class="btn btn-primary float-right ml-3"--}}
                        {{--                                type="button">@lang("فاتو")</button>--}}
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الرقم المرجعي")</th>
                        <th>@lang("البراند")</th>
                        <th>@lang("الموديل")</th>
                        <th>@lang("المستلم")</th>
                        <th>@lang("المخزن")</th>
                        <th>@lang("تكلفة الصيانة")</th>
                        <th>@lang("سعر الشراء")</th>
                        <th>@lang("سعر البيع")</th>
                        <th>@lang("الحالة")</th>
                        @if(auth()->user()->isadmin)
                            <th>@lang("العمليات")</th>

                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($screens as $screen)
                        <tr>

                            <td>{{ $screens->firstItem() + $loop->index}}</td>
                            <td>{{$screen->code}}</td>
                            <td>{{$screen->brand->name}}</td>
                            <td>{{$screen->model}}</td>
                            <td>{{$screen->repairs->first()->receptionist->name}}</td>
                            <td>{{$screen->warehouse->name}}</td>
                            <td>{{ $screen->repair_amount }}</td>
                            <td>{{ $screen->buy->price }}</td>
                            <td>{{ isset($screen->sale->price) ? $screen->sale->price : '-' }}</td>
                            <td><?php echo $screen->statusName ?></td>

                            <td>


                                @if(auth()->user()->isadmin)

                                    <a data-toggle="modal" data-target="#screenSaleModel"
                                       data-id="{{ $screen->id }}"
                                       data-repair_amount="{{ $screen->repair_amount }}"
                                       data-customer="{{$screen->repairs->first()->customer->name}}"
                                       data-price="{{$screen->buy->price }}"
                                       class="btn btn-primary sale"><i
                                            class="fa-solid fa-pen"></i></a>
                                    <a href="{{route('receptionist.screen.buy.return',$screen->id)}}"
                                       data-id="{{ $screen->id }}"
                                       class="btn btn-danger delete"><i class="fa-solid fa-rotate-left"></i></a>
                                @endif


                            </td>


                        </tr>
                    @endforeach

                    </tbody>
                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($screens->hasPages())
                        {{ $screens->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    {{--    @include("screen.model")--}}
    @include("screen.sale.model")
    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {

            $('#model').select2({
                theme: 'bootstrap4',
                tags: true
            });

            "use strict";

            var modal = $('#screenModal');
            var saleModal = $('#screenSaleModel');

            var saveAction = `{{ route('receptionist.screen.save') }}`;
            var saleAction = `{{ route('receptionist.screen.buy') }}`;
            var deleteAction = `{{ route('receptionist.screen.delete') }}`;


            $('.sale').click(function () {
                var data = new $(this).data();
                var form = saleModal.find('form');
                form.attr('action', `${saleAction}/${data.id}`);
                form.find('.customer').text(data.customer);
                form.find('.repair_amount').text(data.repair_amount);
                form.find('[name=price]').val(data.price);
            });


            $('.edit').click(function () {
                var data = $(this).data();
                var form = modal.find('form');
                form.attr('action', `${saveAction}/${data.id}`);
                form.find('[name=name]').val(data.name);
                form.find('[name=code]').val(data.code);
                form.find('[name=brand_id]').val(data.brand);


                form.find('[name=engineer_receive]').val(data.engineer_receive);
                form.find('[name=warehouse]').val(data.warehouse);


                var brandId = data.brand;
                $('#model option').remove();

                $.ajax({
                    url: '{{ route('receptionist.screen.model.getModels') }}',
                    method: 'GET',
                    data: {brand_id: brandId},
                    success: function (data) {
                        $.each(data, function (index, model) {
                            $('#model').append($('<option>', {
                                value: model.name,
                                text: model.name
                            }));
                        });


                    }
                });

                form.find('[name=model]').val(data.model);

                modal.modal('show');
            });


            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
                $('#subcategory_id option:not(:first-child)').remove();
            });


            var deleteModal = $('#deleteModel');
            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });


        })(jQuery);
    </script>
@endpush
