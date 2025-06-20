<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("سلفة")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post"
                      action="{{route('receptionist.user.advance.payment.save')}}">
                    @csrf
                    <input hidden="" name="employee_id" value="{{request()->route('employeeId')}}">
                    <div class="form-group">
                        <label for="amount">@lang("القيمة")</label>
                        <input required type="number" id="amount" name="amount" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="note">@lang("الملاحظة")</label>
                        <textarea id="note" name="note" class="form-control"></textarea>
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




