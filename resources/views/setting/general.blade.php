@extends('layouts.app')

@section("panel")

    <div class="card shadow mb-4">
        <div class="card-header">
            <strong class="card-title">@lang("الاعدادات")</strong>
        </div>
        <div class="card-body">
            <form method="post" action="{{route('receptionist.setting.general.save')}}">
                @csrf
                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="simpleinput">@lang("اسم الشركة")</label>
                            <input type="text" value="{{$general->site_name}}" id="sitename" name="site_name"
                                   class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="simpleinput">@lang("اسم الشركة في اعلى الفاتورة")</label>
                            <input type="text" value="{{$general->invoice_name}}" id="invoice_name" name="invoice_name"
                                   class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="simpleinput">@lang("العملة")</label>
                            <input type="text" value="{{$general->money_sign}}" id="money_sign" name="money_sign"
                                   class="form-control">
                        </div>


                        <div class="form-group mb-3">
                            <label for="insurance_term">@lang("عبارة الضمان")</label>
                            <input type="text" value="{{$general->insurance_term}}" id="insurance_term" name="insurance_term"
                                   class="form-control">
                        </div>

                    </div> <!-- /.col -->
                    <div class="col-md-6">

                        <div class="form-group mb-3">
                            <label for="simpleinput">@lang("العنوان")</label>
                            <input type="text" value="{{$general->address}}" id="address" name="address"
                                   class="form-control">
                        </div>

                        <div class="form-group mb-3">
                            <label for="simpleinput">@lang("رقم الهاتف الاول")</label>
                            <input type="phone" value="{{$general->phone}}" id="phone" name="phone"
                                   class="form-control input-phoneeg">
                        </div>
                        <div class="form-group mb-3">
                            <label for="simpleinput">@lang("رقم الهاتف الثاني")</label>
                            <input type="phone" value="{{$general->sac_phone}}" id="phone" name="sac_phone"
                                   class="form-control input-phoneeg">
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="simpleinput">@lang("كلمة المرور الافتراضية")</label>
                            <input type="text" value="{{$general->default_new_user_pass}}" id="password" name="password"
                                   class="form-control">
                        </div>
                    </div>


                    <div class="col-md-12">
                        <div class="form-group ">
                            <label for="invoice_policy">@lang("شروط فاتورة الاستلام")</label>
                            <textarea id="invoice_policy" name="invoice_policy"
                                      class="form-control">
                                <?php echo $general->invoice_policy ?>
                                    </textarea>
                        </div>
                    </div>
                </div>
                    <button type="submit" class="btn btn-primary">@lang("حفظ التغير")</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <strong class="card-title">@lang("الصور و الشعارات")</strong>
        </div>
        <div class="card-body">
            <form method="post" enctype="multipart/form-data" action="{{route("receptionist.setting.logoIcon.save")}}">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label for="site_logo">@lang("الشعار الاساسي")</label>
                            <div class="avatar-preview">
                                <div class="profilePicPreview logoPicPrev logoPrev"
                                     style="background-image: url({{ getImage(getFilePath('logoIcon').'/site_logo.png', '?'.time()) }})"
                                     onclick="changeImage('site_logo')">
                                </div>
                            </div>
                            <input type="file" id="site_logo" name="site_logo" class="form-control-file"
                                   onchange="previewImage(this)" hidden="">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label for="small_logo">@lang("الشعار الصغير")</label>
                            <div class="avatar-preview">
                                <div class="profilePicPreview logoPicPrev logoPrev"
                                     style="background-image: url({{ getImage(getFilePath('logoIcon').'/small_logo.png', '?'.time()) }})"
                                     onclick="changeImage('small_logo')">
                                </div>
                            </div>
                            <input type="file" id="small_logo" name="small_logo" class="form-control-file"
                                   onchange="previewImage(this)" hidden="">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group mb-2">
                            <label for="invoice_logo">@lang("شعار الفواتير")</label>
                            <div class="avatar-preview">
                                <div class="profilePicPreview logoPicPrev logoPrev"
                                     style="background-image: url({{ getImage(getFilePath('logoIcon').'/invoice_logo.png', '?'.time()) }})"
                                     onclick="changeImage('invoice_logo')">
                                </div>
                            </div>
                            <input type="file" id="invoice_logo" name="invoice_logo" class="form-control-file"
                                   onchange="previewImage(this)" hidden="">
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">@lang("حفظ التغير")</button>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            var preview = $(input).closest('.form-group').find('.profilePicPreview');
            var file = input.files[0];
            var reader = new FileReader();

            reader.onloadend = function () {
                preview.css('background-image', 'url(' + reader.result + ')');
            }

            if (file) {
                reader.readAsDataURL(file);
            }
        }

        function changeImage(inputId) {
            $('#' + inputId).click(); // Trigger the file input click event
        }


    </script>

@endsection


@push('script')
    <script>
        bkLib.onDomLoaded(function () {
            // Initialize NicEdit
            var nicInstance = new nicEditor({fullPanel: true}).panelInstance('invoice_policy');

        });
    </script>
@endpush
