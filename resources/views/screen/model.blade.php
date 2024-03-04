<div class="modal fade" id="screenModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("الشاشة")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    @csrf

                    <div class="form-group">
                        <label for="name">@lang("الماركة")</label>
                        <select id="brand_id" name="brand_id" class="form-control select2">
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="name">@lang("الموديل")</label>
                        <select id="model" name="model" class="form-control select2">
                            <option value="">@lang("اختر الموديل")</option>
                            <!-- Populate models dynamically using AJAX based on the selected brand -->
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="serial">@lang("السيريال")</label>
                        <input type='text' name="serial" id="serial" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="name">@lang("مهندس الإستلام")</label>
                        <select name="engineer_receive" id="engineer_receive" class="form-control select2">
                            @foreach($engineers as $engineer)
                                <option value="{{ $engineer->id }}">{{ $engineer->name }}</option>
                            @endforeach
                        </select>
                    </div>



                    <div class="form-group">
                        <label for="name">@lang("المخزن")</label>
                        <select name="warehouse" id="warehouse" class="form-control select2">
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
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




