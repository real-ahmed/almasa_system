@extends("layouts.app")

@section('panel')

    <div class="card shadow">
        <div class="card-body">
            <div class="toolbar row mb-3">
                <div class="col">
                    <form class="form-inline" action="{{route('warehouse.employee.subcategory.all')}}">

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
                        <th>@lang("التصنيف الاساسي")</th>
                        <th>@lang("تاريخ الاضافة")</th>
                        <th>@lang("العمليات")</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($subcategories as $subcategory)
                        <tr>
                            <td>{{$subcategories->firstItem() + $loop->index}}</td>
                            <td>{{$subcategory->name}}</td>
                            <td>{{$subcategory->category->name}}</td>
                            <td> {{date_format($subcategory->created_at,'m/d/y h:iA')}}</td>
                            <td><a data-toggle="modal" data-target="#repairModal" data-id="{{ $subcategory->id }}"
                                   data-name="{{ $subcategory->name??null }}"
                                   data-category_id="{{ $subcategory->category->id }}" class="btn btn-primary edit"><i
                                        class="fa-solid fa-pen"></i></a>
                                @if(auth()->user()->isreceptionist)
                                    <a data-toggle="modal" data-target="#deleteModel" data-id="{{ $subcategory->id }}"
                                       class="btn btn-danger delete"><i
                                            class="fa-solid fa-trash"></i></a>
                                @endif</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>

                <nav aria-label="Table Paging" class="mb-0 text-muted">
                    @if ($subcategories->hasPages())
                        {{ $subcategories->links() }}
                    @endif
                </nav>
            </div>
        </div>
    </div>

    @include("subcategory.model")
    @include("models.delete")

@endsection


@push('script')
    <script>
        (function ($) {

            "use strict";

            var modal = $('#repairModal');

            var saveAction = `{{ route('warehouse.employee.subcategory.save') }}`;
            var deleteAction = `{{ route('warehouse.employee.subcategory.delete') }}`;


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
