<div class="modal fade" id="requestModel" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("المتجات")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('item.request.save')}}">
                    @csrf
                    <div class="form-group">
                        <label for="request_quantity">@lang("المنتج")</label>

                        <select class="form-control select2" name="product_id">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->category->name }} - {{ $product->subcategory->name??null }} - {{ $product->brand->name ?? null }} -{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="request_quantity">@lang("الكمية المطلوبة")</label>
                        <input required type="number" value="1" min="1" id="selling_price" name="request_quantity"
                               class="form-control">
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary"
                                data-dismiss="modal">@lang("اغلاق")</button>
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
                    data: {category_id: categoryId},
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
