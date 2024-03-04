<div class="modal fade" id="repairModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("نوع صيانة")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('admin.repair.type.save')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang("الاسم")</label>
                        <input required type="text" id="name" name="name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">@lang("البونص")</label>
                        <div class="input-group">

                            <input type="number" step='any' name="default_bonus" class="form-control"
                                   aria-label="Amount (to the nearest dollar)">
                            <select name="bonus_type" class="form-control input-group-append">
                                <option value="0" class="input-group-text">{{$general->money_sign}}</option>
                                <option value="1" class="input-group-text">%</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="recipient-name" class="col-form-label">@lang("سعر البيع")</label>
                        <div class="input-group">

                            <input type="number" step='any' name="price" value="" class="form-control"
                                   aria-label="Amount (to the nearest dollar)">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$general->money_sign}}</span>
                            </div>
                        </div>
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




