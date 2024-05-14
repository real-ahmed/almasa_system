@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('partition.all')}}">

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
                                type="button">@lang("+ قسم جديد")</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 class="card-title">@lang('الكل')</h5>
                            <p class="card-text">{{$screenComponentCount }} @lang('منتج')</p>
                            <div class="btn-group" role="group" aria-label="Basic example">
                                <a href="{{route('component.all')}}" class="btn btn-success "><i
                                        class="fa-solid fa-boxes-stacked"></i> @lang('عرض')</a>
                            </div>
                        </div>
                    </div>
                </div>
                @foreach($partitions as $partition)
                    <div class="col-lg-4 mb-4">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h5 class="card-title">{{$partition->name}}</h5>
                                <p class="card-text">{{$partition->screenComponent->count() }} @lang('منتج')</p>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{route('component.all',$partition->id)}}" class="btn btn-success "><i
                                            class="fa-solid fa-boxes-stacked"></i> @lang('عرض')</a>

                                    @if(auth()->user()->isWarehouseEmployee)
                                        <a data-toggle="modal" data-target="#repairModal" data-id="{{ $partition->id }}"
                                           data-name="{{ $partition->name }}" class="btn btn-primary edit"><i
                                                class="fa-solid fa-pen"></i> @lang('تعديل')</a>
                                    @endif
                                    @if(auth()->user()->isadmin)
                                        <a data-toggle="modal" data-target="#deleteModel"
                                           data-id="{{ $partition->id }}"
                                           data-name="{{ $partition->name }}" class="btn btn-danger delete"><i
                                                class="fa-solid fa-trash"></i> @lang('حذف')</a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <nav aria-label="Table Paging" class="mb-0 text-muted">
                @if ($partitions->hasPages())
                    {{ $partitions->links() }}
                @endif
            </nav>
        </div>
    </div>

    @include("storage_partition.model")
    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#repairModal');

            var saveAction = `{{ route('item.partition.save') }}`;
            var deleteAction = `{{ route('admin.partition.delete') }}`;


            $('.edit').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=name]').val(data.name);
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
