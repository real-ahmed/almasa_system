<div class="modal fade" id="repairModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('receptionist.bonus.save')}}">
                    @csrf
                    @if(@$records[0]->status ==0 )
                        <div class="form-group">
                            <label for="amount">@lang("وقت الحضور")</label>
                            <input required type="text" id="arrival_time" name="arrival_time" class="form-control timepicker">
                        </div>
                    @elseif(@$records[0]->status ==1)
                        <div class="form-group">
                            <label for="amount">@lang("وقت الانصراف")</label>
                            <input required type="text" id="departure_time" name="departure_time" class="form-control timepicker">
                        </div>

                    @else
                        <div class="form-group">
                            <label for="amount">@lang("وقت الحضور")</label>
                            <input required type="text" id="arrival_time"  name="arrival_time" class="form-control timepicker">
                        </div>
                        <div class="form-group">
                            <label for="amount">@lang("وقت الانصراف")</label>
                            <input required type="text" id="departure_time" name="departure_time" class="form-control timepicker">
                        </div>

                    @endif


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


