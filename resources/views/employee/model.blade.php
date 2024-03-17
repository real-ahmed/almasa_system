<div class="modal fade" id="varyModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel"
     style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("موظف جديد")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('receptionist.employee.create')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang("الاسم")</label>
                        <input required type="text" id="name" name="name" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="email">@lang("اسم المستخدم (لتسجيل الدخول)")</label>
                        <input required type="text" id="email" name="email" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="phone">@lang("رقم الهاتف")</label>
                        <input required type="phone" id="phone" name="phone" class="form-control input-phoneeg">
                    </div>
                    <div class="form-group">
                        <label for="role">@lang("الوظيقة")</label>
                        <select id="role" name="role" class="form-control">
                            <option value="1">@lang("مهندس") </option>
                            <option value="2">@lang("موظف استقبال") </option>
                            <option value="3">@lang("مسؤول مخازن") </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">@lang("الراتب الشهري")</label>
                        <div class="input-group">

                            <input type="text" name="salary" class="form-control"
                                   aria-label="Amount (to the nearest dollar)">
                            <div class="input-group-append">
                                <span class="input-group-text">{{$general->money_sign}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="salary_date"
                               class="col-form-label"><small>@lang("يوم القبض")</small>@lang("تاريخ التعين")</label>
                        <div class="input-group">
                            <input type="text" class="form-control drgpicker" id="date-input1" name="salary_date"
                                   value="{{date('m/d/Y')}}" aria-describedby="button-addon2">
                            <div class="input-group-append">
                                <div class="input-group-text" id="button-addon-date"><span
                                        class="fa-solid fa-calendar-days"></span></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="amount">@lang("وقت الحضور")</label>
                        <input required type="text" id="arrival_time" name="arrival_time"
                               class="form-control timepicker">
                    </div>
                    <div class="form-group">
                        <label for="amount">@lang("وقت الانصراف")</label>
                        <input required type="text" id="departure_time" name="departure_time"
                               class="form-control timepicker">
                    </div>
                    <div class="form-group">
                        <label for="simpleinput">@lang("كلمة المرور الافتراضية")</label>
                        <strong>{{$general->default_new_user_pass}}</strong>
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
            $('.timepicker').timepicker({
                timeFormat: 'h:mm p',
                interval: 60,
                minTime: '00:00am',
                maxTime: '11:00pm',
                defaultTime: '08:00am',
                startTime: '00:00',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        })(jQuery);
    </script>
@endpush
