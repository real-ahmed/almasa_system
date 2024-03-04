<div class="modal fade" id="deleteModel" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verticalModalTitle">@lang("هل انت متاكد من الحذف")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">@lang("يجب عليك التاكد من ان العنصر الذي تريد حذفة غير مرتبط بعناصر اخرى")  </div>

            <div class="modal-footer">
                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">@lang("الغاء")</button>
                <a id="delete" href="" class="btn mb-2 btn-danger">@lang("حذف")</a>
            </div>
        </div>
    </div>
</div>
