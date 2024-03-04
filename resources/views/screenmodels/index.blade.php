@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('receptionist.screen.model.all')}}">

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
                                type="button">@lang("+ موديل جديد")</button>
                    </div>
                </div>
            </div>
            <div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang("رقم")</th>
                        <th>@lang("الموديل")</th>
                        <th>@lang("البراند")</th>
                        <th>@lang("العمليات")</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($screenModels as $screenModel)
                        <tr>
                            <td>{{$screenModels->firstItem() + $loop->index}}</td>
                            <td>{{$screenModel->name}}</td>
                            <td>{{$screenModel->brand->name}}</td>
                            <td><a data-toggle="modal" data-target="#repairModal" data-id="{{ $screenModel->id }}"
                                   data-name="{{ $screenModel->name }}"
                                   data-category_id="{{ $screenModel->brand->id }}" class="btn btn-primary edit"><i
                                            class="fa-solid fa-pen"></i></a>
                                <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $screenModel->id }}"
                                   class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a></td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($screenModels->hasPages())
                        {{ $screenModels->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    @include("screenmodels.model")
    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#repairModal');

            var saveAction = `{{ route('receptionist.screen.model.save') }}`;
            var deleteAction = `{{ route('receptionist.screen.model.delete') }}`;


            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=name]').val(data.name);
                modal.find('[name=category_id]').val(data.category_id);


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
