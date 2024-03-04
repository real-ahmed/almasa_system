@extends("layouts.app")

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("فاتورة استلام")</strong>
            </div>

            <div class="card-body">
                <form method="post" action="{{ route('receptionist.screen.receive.save', $repair->id ?? '') }}"
                      onsubmit="return validateScreens()">
                    @csrf
                    <strong class="card-title">@lang("معلومات العميل")</strong>

                    <div class="row card-body">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="customerPhone">@lang("رقم الهاتف")</label>
                                <input type="text" id="customerPhone" name="customer_phone" class="form-control"
                                       placeholder="رقم الهاتف" value="{{$repair->customer->phone?? ''}}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="customerAddress">@lang("عنوان العميل")</label>
                                <input type="text" id="customerAddress" name="customer_address" class="form-control"
                                       placeholder="عنوان العميل" value="{{$repair->customer->address?? ''}}"
                                       @readonly(isset($repair->customer->address)) required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="customerName">@lang("اسم العميل")</label>
                                <input type="text" id="customerName" name="customer_name" class="form-control"
                                       placeholder="اسم العميل" value="{{$repair->customer->name?? ''}}"
                                       @readonly(isset($repair->customer->name)) required>
                            </div>
                        </div>
                    </div>

                    <strong class="card-title">@lang("معلومات الفاتورة")</strong>

                    <div class="row card-body">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="receive_date" class="col-form-label">@lang('تاريخ الاستلام')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control drgpicker" id="date-input1"
                                           name="receive_date"
                                           value="{{ isset($repair->receive_date)? date('m/d/Y',strtotime($repair->receive_date)) :date('m/d/Y') }}"
                                           aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fa-solid fa-calendar-days"></span></div>
                                    </div>
                                </div>


                            </div>


                            <div class="form-group mb-3" @if(!auth()->user()->isadmin) style="display: none" @endif>
                                <label for="receive_date" class="col-form-label">@lang('موظف الاستقبال')</label>
                                <div class="input-group">
                                    <select name="receptionist_id" class="form-control">
                                        <option value="">@lang("اختار موظف")</option>
                                        @foreach($receptionists as $receptionist)
                                            <option value="{{ $receptionist->id }}"
                                                    @if($receptionist->id == auth()->user()->id || (isset($repair->receptionist->id) && ($receptionist->id == $repair->receptionist->id)))
                                                        selected
                                                @endif>
                                                {{ $receptionist->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="expected_delivery_date"
                                       class="col-form-label">@lang('تاريخ التسليم المتوقع')</label>
                                <div class="input-group">
                                    <input type="text" class="form-control drgpicker" id="date-input2"
                                           name="expected_delivery_date"
                                           value="{{ isset($repair->expected_delivery_date)? date('m/d/Y',strtotime($repair->expected_delivery_date)) :date('m/d/Y') }}"
                                           aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <div class="input-group-text" id="button-addon-date"><span
                                                class="fa-solid fa-calendar-days"></span></div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group mb-3">
                                <label for="paid">@lang("عربون")</label>
                                <input required value="{{$repair->paid?? "0"}}" min="0" type="number" id="paid" name="paid" class="form-control">
                            </div>
                        </div>
                    </div>

                    <strong class="card-title">@lang("الشاشات")</strong>

                    <div class="card-body">
                        <div class="toolbar row mb-3">
                            <div class="col">
                                <div class="form-row">
                                    <div class="input-group">
                                        <select id="brand_id" class="form-control select2">
                                            <option value="">@lang("اختر الماركة")</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                        <select id="model_id" class="form-control select2">
                                            <option value="">@lang("اختر الموديل")</option>
                                            <!-- Populate models dynamically using AJAX based on the selected brand -->
                                        </select>
                                        <select class="form-control select2" id="engineer_receive">
                                            <option value="">@lang("اختر مهندس الإستلام")</option>
                                            @foreach($engineers as $engineer)
                                                <option value="{{ $engineer->id }}">{{ $engineer->name }}</option>
                                            @endforeach
                                        </select>

                                        <select class="form-control select2" id="warehouse">
                                            <option value="">@lang("اختر المخزن")</option>
                                            @foreach($warehouses as $warehouse)
                                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="addScreen">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover" id="repairScreensTable">
                            <thead>
                            <tr>
                                <th>@lang("الكود")</th>
                                <th>@lang("الماركة")</th>
                                <th>@lang("الموديل")</th>
                                <th>@lang("السيريال")</th>
                                <th>@lang("المشكلة")</th>

                                <th>@lang("مهندس الإستلام")</th>
                                <th>@lang("المخزن")</th>
                                <th>@lang("العمليات")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($repair))
                                @foreach($repair->screens as $screen)
                                    <tr>

                                        <td>
                                            <input type="hidden"
                                                   name="repair_screens[{{ $loop->index }}][screen_code]"
                                                   value="{{ $screen->code }}">

                                            <input type="hidden"
                                                   name="repair_screens[{{ $loop->index }}][screen_brand]"
                                                   value="{{$screen->brand_id}}">
                                            <input type="hidden"
                                                   name="repair_screens[{{ $loop->index }}][screen_model]"
                                                   value="{{$screen->model}}">
                                            <input type="hidden"
                                                   name="repair_screens[{{ $loop->index }}][screen_engineer_receive]"
                                                   value="{{$screen->engineer_receive_id}}">

                                            <input type="hidden"
                                                   name="repair_screens[{{ $loop->index }}][screen_warehouse]"
                                                   value="{{$screen->warehouse_id}}">

                                            <span class="screen-code">{{ $screen->code }}</span>
                                        </td>

                                        <td>
                                            <span class="screen-brand">{{ $screen->brand->name }}</span>
                                        </td>
                                        <td>
                                            <span class="screen-model">{{ $screen->model }}</span>
                                        </td>
                                        <td>
                                        <input type="text"
                                                   name="repair_screens[{{ $loop->index }}][serial]"
                                                   value="{{$screen->serial}}" class="form-control" required>
                                        </td>


                                        <td>
                                            <input type="text"
                                                   name="repair_screens[{{ $loop->index }}][issue]"
                                                   value="{{$screen->issue}}" class="form-control" required>
                                        </td>
                                        <td>
                                            <span
                                                class="engineer-receive">{{ $screen->engineer_receive->name }}</span>
                                        </td>


                                        <td>
                                            <span
                                                class="warehouse">{{ $screen->warehouse->name }}</span>
                                        </td>
                                        <td>

                                            @if($screen->unlinked())
                                                <button class="btn btn-danger removeScreen" type="button">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        @lang("حفظ")
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            $('#model_id').select2({
                theme: 'bootstrap4',
                tags: true
            });

            const customerPhoneInput = $('#customerPhone');
            const customerNameInput = $('#customerName');
            const customerAddressInput = $('#customerAddress');

            customerPhoneInput.on('input', function () {
                const phoneNumber = customerPhoneInput.val();
                customerNameInput.val('');
                customerNameInput.prop('readonly', false);
                customerAddressInput.prop('readonly', false);

                $.ajax({
                    url: '{{ route('receptionist.customer.get.info') }}',
                    method: 'GET',
                    data: {phone: phoneNumber},
                    success: function (response) {
                        if (response.name !== '') {
                            customerNameInput.val(response.name);
                            customerAddressInput.val(response.address);
                            customerNameInput.prop('readonly', true);
                            customerAddressInput.prop('readonly', true);
                        }
                    },
                });
            });

            $('#brand_id').change(function () {
                var brandId = $(this).val();
                $.ajax({
                    url: '{{ route('receptionist.screen.model.getModels') }}',
                    method: 'GET',
                    data: {brand_id: brandId},
                    success: function (data) {
                        $('#model_id option:not(:first-child)').remove();
                        $.each(data, function (index, subcategory) {
                            $('#model_id').append($('<option>', {
                                value: subcategory.id,
                                text: subcategory.name
                            }));
                        });
                    }
                });
            });

            const repairScreensTableBody = $('#repairScreensTable tbody');

            $('#addScreen').click(function () {
                const screenBrand = $('#brand_id').val();
                const screenModel = $('#model_id option:selected').text();
                const engineerReceive = $('#engineer_receive').val();
                const warehouse = $('#warehouse').val();
                const code = generateScreenCode();

                if (!screenBrand || !screenModel) {
                    return;
                }

                const newRow = $('<tr>').html(`
                                                <td>
                                                    <input type="hidden" name="repair_screens[${repairScreensTableBody.children().length}][screen_code]" value="${code}">

                                                    <input type="hidden" name="repair_screens[${repairScreensTableBody.children().length}][screen_brand]" value="${screenBrand}">
                                                    <input type="hidden" name="repair_screens[${repairScreensTableBody.children().length}][screen_model]" value="${screenModel}">
                                                    <input type="hidden" name="repair_screens[${repairScreensTableBody.children().length}][screen_engineer_receive]" value="${engineerReceive}">
                                                    <input type="hidden" name="repair_screens[${repairScreensTableBody.children().length}][screen_warehouse]" value="${warehouse}">

                                                    <span class="screen-code">${code}</span>
                                                </td>
                                                <td>
                                                    <span class="screen-brand">${$('#brand_id option:selected').text()}</span>
                                                </td>
                                                <td>
                                                    <span class="screen-model">${$('#model_id option:selected').text()}</span>
                                                </td>
                                                <td>
                                                    <input type="text"  required name="repair_screens[${repairScreensTableBody.children().length}][serial]"  class="form-control">
                                                </td>

                                                <td>
                                                    <input type="text"  required name="repair_screens[${repairScreensTableBody.children().length}][issue]"  class="form-control">
                                                </td>
                                                <td>
                                                    <span class="engineer-receive">${$('#engineer_receive option:selected').text()}</span>
                                                </td>

                                                <td>
                                                    <span class="warehouse">${$('#warehouse option:selected').text()}</span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger removeScreen" type="button">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </td>
                                            `);


                repairScreensTableBody.append(newRow);
            });

            repairScreensTableBody.on('click', '.removeScreen', function () {
                $(this).closest('tr').remove();
                updateTotalRepairAmount();
            });

            repairScreensTableBody.on('input', 'input[name^="repair_screens"]', function () {
                updateTotalRepairAmount();
            });

            function updateTotalRepairAmount() {
                let totalRepairAmount = 0;

                repairScreensTableBody.find('tr').each(function () {
                    totalRepairAmount += 1;
                });

                $('#totalRepairAmount').text(totalRepairAmount.toFixed(2));
            }

            function generateScreenCode() {
                const timestamp = new Date().getTime();
                return 'SCR' + timestamp;
            }
        });
    </script>
    <script>
        function validateScreens() {
            var numberOfScreens = $('#repairScreensTable tbody tr').length;

            if (numberOfScreens < 1) {
                alert("اضف شاشة على الاقل");
                return false; // Prevent form submission
            }


            return true;
        }
    </script>

@endpush
