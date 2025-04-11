<div class="modal fade" id="pauseModal" tabindex="-1" role="dialog" aria-labelledby="pauseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('receptionist.attendance.pause',['id' => $record->id])}}">
                    @csrf
                    <div class="form-group">
                        <label for="pause_start">@lang("وقت بداية الاستراحة")</label>
                        <input required type="text" id="pause_start" name="pause_start" class="form-control timepicker">
                    </div>
                    <div class="form-group">
                        <label for="pause_end">@lang("وقت نهاية الاستراحة")</label>
                        <input required type="text" id="pause_end" name="pause_end" class="form-control timepicker">
                    </div>
                    <input type="hidden" name="attendance_id" id="attendance_id">
                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">@lang("اغلاق")</button>
                        <button type="submit" class="btn mb-2 btn-primary">@lang("حفظ")</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
