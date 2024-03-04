<div class="modal fade" id="noteModel" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("ملاحظة")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('receptionist.repair.deliver.note.save',$deliver->id)}}">
                    @csrf
                    <input type="text" hidden="" name="employee_id">
                    <div class="form-group">
                        <label for="note">@lang("الملاحظة")</label>
                        <textarea   id="note" name="note" class="form-control">
                            {{$deliver->note}}
                        </textarea>
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
        (function ($) {

            "use strict";

            var modal = $('#bonusModal');

            var saveAction = `{{ route('receptionist.bonus.save') }}`;


            $('.edit-bonus').click(function () {
                var data = new $(this).data();
                modal.find('form').attr('action', `${saveAction}/${data.id}`);
                modal.find('[name=amount]').val(data.amount);
                modal.find('[name=employee_id]').val(data.employee_id);
            });

            modal.on('hidden.bs.modal', function () {
                modal.find('form')[0].reset();
            });


        })(jQuery);
    </script>
@endpush
