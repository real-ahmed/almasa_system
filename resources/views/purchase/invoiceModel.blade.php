<div class="modal fade " id="invoiceModal" tabindex="-1" role="dialog" aria-labelledby="verticalModalTitle"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" @style(['max-width: 815px;']) role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verticalModalTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class='print'></div>
            <div class="modal-footer">
                <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">@lang('اغلاق')</button>
                <button type="button" class="btn mb-2 btn-primary btn-print">@lang('طباعة')</button>
            </div>
        </div>
    </div>
</div>
