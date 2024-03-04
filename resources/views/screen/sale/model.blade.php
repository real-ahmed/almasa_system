<div class="modal fade" id="screenSaleModel" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("تحويل للبيع")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="">
                    @csrf

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>@lang('العميل') :</strong> <span class="customer" ></span>
                        </div>
                        <div class="col-md-6">
                            <strong>@lang("تكلفة الصيانة")</strong> <span class="repair_amount"></span> {{$general->money_sign}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">@lang("سعر الشراء بعد خصم الصيانة")</label>
                        <input required min="0" type="number" id="price" name="price" class="form-control">
                    </div>



                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">@lang("اغلاق")</button>
                        <button type="submit" class="btn mb-2 btn-success">@lang("تحويل")</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




