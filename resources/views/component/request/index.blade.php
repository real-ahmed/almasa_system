@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('item.request.all')}}">

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


                        <button data-toggle="modal" data-target="#requestModel"
                                class="btn btn-primary float-right ml-3 new"
                                type="button">@lang("+ طلب جديد")</button>

                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الاسم المنتج")</th>
                        <th>@lang("الرقم التسلسلي")</th>
                        <th>@lang("البراند")</th>
                        <th>@lang("الفئة")</th>
                        <th>@lang("الفئة الفرعية")</th>
                        <th>@lang("الموظف")</th>
                        <th>@lang("الكمية المطلوبة")</th>
                        <th>@lang("الكمية المتوفرة")</th>
                        @if(auth()->user()->isWarehouseEmployee)
                            <th>@lang("العمليات")</th>
                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($components as $component)
                        <tr>
                            <td>{{$components->firstItem() + $loop->index}}</td>
                            <td>{{$component->name}}</td>
                            <td>{{$component->code}} </td>
                            <td>{{$component->brand->name??'-'}}</td>
                            <td>{{$component->category->name}}</td>
                            <td>{{$component->subcategory->name ?? "-"}}</td>
                            <td>{{$component->manualItemRequest->employee->name??'النظام'}}</td>

                            <td>{{$component->RequestedQuantity}}</td>
                            <td>{{$component->instockQuantity}}</td>

                            @if(auth()->user()->isWarehouseEmployee )
                                <td>

                                    <button
                                        @disabled(!isset($component->manualItemRequest)) data-id="{{ isset($component->manualItemRequest)? $component->manualItemRequest->id : '' }}"
                                        data-name="{{ $component->name }}"
                                        data-product_id="{{ $component->id }}"
                                        data-request_quantity="{{ $component->RequestedQuantity }}"
                                        data-toggle="modal" data-target="#requestModel"
                                        class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></button>
                                    <button data-toggle="modal" data-target="#deleteModel"
                                            @disabled(!isset($component->manualItemRequest)) data-id="{{ isset($component->manualItemRequest)? $component->manualItemRequest->id : '' }}"
                                            class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></button>
                                </td>
                            @endif

                        </tr>
                    @endforeach


                    </tbody>
                </table>
                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($components->hasPages())
                        {{ $components->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>
    @include("component.request.model")
    @include("models.delete")

@endsection

@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#requestModel');

            var saveAction = `{{ route('item.request.save') }}`;
            var deleteAction = `{{ route('item.request.delete') }}`;


            $('.edit').click(function () {
                var data = $(this).data();
                var form = modal.find('form');
                form.attr('action', `${saveAction}/${data.id}`);
                form.find('[name=request_quantity]').val(data.request_quantity);
                form.find('[name=product_id]').val(data.product_id);
                form.find('[name=product_id]').trigger('change');


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

