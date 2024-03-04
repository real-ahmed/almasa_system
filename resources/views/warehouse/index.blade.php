@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('admin.warehouse.all',['type'=>$type])}}">

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
                        <a data-toggle="modal" data-target="#repairModal"
                           class="btn btn-primary float-right ml-3"
                           type="button">@lang("+ محزن جديد")</a>
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الاسم")</th>
                        <th>@lang("تاريخ الاضافة")</th>
                        <th>@lang("العمليات")</th>

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($warehouses as $warehouse)
                        <tr>
                            <td>{{$warehouses->firstItem() + $loop->index}}</td>
                            <td>{{$warehouse->name}}</td>
                            <td> {{date_format($warehouse->created_at,'m/d/y h:iA')}}</td>


                            <td>
                                <a data-toggle="modal" data-target="#repairModal" data-id="{{ $warehouse->id }}"
                                   data-name="{{ $warehouse->name }}"
                                   class="btn btn-primary edit"><i
                                        class="fa-solid fa-pen"></i></a>
                                <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $warehouse->id }}"
                                   class="btn btn-danger delete"><i
                                        class="fa-solid fa-trash"></i></a>
                            </td>


                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($warehouses->hasPages())
                        {{ $warehouses->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>


    @include("models.delete")
    @include("warehouse.model")

@endsection


@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#repairModal');


            var saveAction = `{{ route('admin.warehouse.save',['type'=>$type]) }}`;
            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=name]').val(data.name);
            });

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
            });


            var deleteAction = `{{ route('admin.warehouse.delete') }}`;

            var deleteModal = $('#deleteModel');
            $('.delete').click(function () {
                var data = new $(this).data();
                deleteModal.find('#delete').attr('href', `${deleteAction}/${data.id}`);
            });


        })(jQuery);
    </script>
@endpush
