<div class="modal fade" id="componentModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("المتجات")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('item.component.save')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang("الاسم")</label>
                        <input required type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="name">@lang("الرقم التسلسلي")</label>
                        <input required type="text" id="code" name="code" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">@lang("البراند")</label>
                        <select  name="brand_id" id="brand_id" class="form-control">
                            <option value="" selected>@lang("الرجاء اختيار البراند")</option>
                            @foreach($brands as $brand)
                                <option value="{{$brand->id}}">{{$brand->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">@lang("التصنيف الاساسي")</label>
                        <select required name="category_id" id="category_id" class="form-control">
                            <option disabled selected>@lang("الرجاء اختيار التصنيف الاساسي")</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">@lang("التصنيف الفرعي")</label>
                        <select  name="subcategory_id" id="subcategory_id" class="form-control">
                            <option value="" selected>@lang("بدون تصنيف فرعي")</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="auto_request_quantity">@lang("الكمية لانشاء طلب تلقائي")</label>
                        <input  type="number"  min="0" id="auto_request_quantity" name="auto_request_quantity" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="name">@lang("سعر البيع")</label>
                        <input required type="text" id="selling_price" name="selling_price" class="form-control">
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">@lang("اغلاق")</button>
                        <button type="submit" class="btn mb-2 btn-primary">@lang("حفظ")</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




@push('script')
    <script>
        $(document).ready(function () {
            // Event listener for category select change
            $('#category_id').change(function () {
                var categoryId = $(this).val();
                // AJAX request to fetch subcategories for the selected category
                $.ajax({
                    url: '{{ route('warehouse.employee.subcategory.getSubcategories') }}',
                    method: 'GET',
                    data: { category_id: categoryId },
                    success: function (data) {
                        // Clear existing options
                        $('#subcategory_id option:not(:first-child)').remove();
                        // Add new options based on the response
                        $.each(data, function (index, subcategory) {
                            $('#subcategory_id').append($('<option>', {
                                value: subcategory.id,
                                text: subcategory.name
                            }));
                        });
                    }
                });
            });
        });
    </script>

@endpush
