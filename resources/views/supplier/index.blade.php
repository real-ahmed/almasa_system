@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('warehouse.employee.supplier.all')}}">

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
                        <button data-toggle="modal" data-target="#supplierModal" class="btn btn-primary float-right ml-3"
                                type="button">@lang("+ تاجر جديد")</button>
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الاسم")</th>
                        <th>@lang("رقم الهاتف")</th>

                        <th>@lang("العمليات")</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($suppliers as $supplier)
                        <tr>
                            <td>{{$suppliers->firstItem() + $loop->index}}</td>
                            <td>{{$supplier->name}}</td>
                            <td>{{$supplier->phone}}</td>

                            <td>
                                <a data-toggle="modal" data-target="#supplierModal" data-id="{{ $supplier->id }}"
                                   data-name="{{ $supplier->name }}" class="btn btn-primary edit"><i
                                        class="fa-solid fa-pen"></i></a>
                                @if(auth()->user()->isadmin)

                                <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $supplier->id }}"
                                   data-name="{{ $supplier->name }}" data-phoen="{{ $supplier->phoen }}"  class="btn btn-danger delete"><i
                                        class="fa-solid fa-trash"></i></a>
                                @endif
                            </td>


                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($suppliers->hasPages())
                        {{ $suppliers->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    @include("supplier.model")
    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#supplierModal');

            var saveAction = `{{ route('warehouse.employee.supplier.save') }}`;
            var deleteAction = `{{ route('warehouse.employee.supplier.delete') }}`;


            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=name]').val(data.name);
                modal.find('[name=phone]').val(data.phone);

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
