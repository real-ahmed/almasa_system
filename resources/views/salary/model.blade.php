<div class="modal fade" id="salaryModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("التصنيف")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('admin.salary.save')}}">
                    @csrf
                    <div class="form-group">
                        <label for="salary_amount">@lang("القيمة")</label>
                        <input required type="number" id="salary_amount" name="salary_amount" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="bonuses_amount">@lang("القيمة")</label>
                        <input required type="number" id="bonuses_amount" name="bonuses_amount" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="status">@lang("الحالة")</label>
                        <select id="status" name="status" class="form-control">
                            <option value="0">@lang("قيد المعالجة") </option>
                            <option value="1">@lang("تم الدفع") </option>
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




