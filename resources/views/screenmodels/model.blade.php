<div class="modal fade" id="repairModal" tabindex="-1" role="dialog" aria-labelledby="varyModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="varyModalLabel">@lang("اضافة موديل")</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('receptionist.screen.model.save')}}">
                    @csrf
                    <div class="form-group">
                        <label for="name">@lang("الاسم")</label>
                        <input required type="text" id="name" name="name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="col-form-label">@lang("البراند")</label>
                            <select required name="brand_id" class="form-control">
                                <option disabled selected >@lang("الرجاء اختيار البراند")</option>
                                @foreach($brands as $brand)
                                    <option value="{{$brand->id}}" >{{$brand->name}}</option>
                                @endforeach
                            </select>

                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">@lang("اغلاق")</button>
                        <button type="submit" class="btn mb-2 btn-primary">@lang("حفظ")</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>




