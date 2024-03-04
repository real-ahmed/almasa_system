@extends("layouts.app")

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("فاتورة بيع شاشة")</strong>
            </div>

            <div class="card-body">
                <form method="post" action="{{ route('receptionist.sale.screen.save', $invoice->id ?? '') }}"
                      onsubmit="return validateScreens()">
                    @csrf
                    <strong class="card-title">@lang("معلومات العميل")</strong>

                    <div class="row card-body">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="customerPhone">@lang("رقم الهاتف")</label>
                                <input type="text" id="customerPhone" name="customer_phone" class="form-control"
                                       placeholder="رقم الهاتف" value="{{$invoice->customer->phone?? ''}}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="customerAddress">@lang("عنوان العميل")</label>
                                <input type="text" id="customerAddress" name="customer_address" class="form-control"
                                       placeholder="اسم العميل" value="{{$invoice->customer->address?? ''}}"
                                       @readonly(isset($invoice->customer->address)) required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="customerName">@lang("اسم العميل")</label>
                                <input type="text" id="customerName" name="customer_name" class="form-control"
                                       placeholder="اسم العميل" value="{{$invoice->customer->name?? ''}}"
                                       @readonly(isset($invoice->customer->name)) required>
                            </div>
                        </div>
                    </div>

                    <strong class="card-title">@lang("معلومات الفاتورة")</strong>

                    <div class="row card-body">
                        <div class="col-md-6">

                            <div class="form-group mb-3" @if(!auth()->user()->isadmin) style="display: none" @endif>
                                <label for="receive_date" class="col-form-label">@lang('موظف الاستقبال')</label>
                                <div class="input-group">
                                    <select name="receptionist_id" class="form-control">
                                        <option value="">@lang("اختار موظف")</option>
                                        @foreach($receptionists as $receptionist)
                                            <option value="{{ $receptionist->id }}"
                                                    @if($receptionist->id == auth()->user()->id || (isset($invoice->receptionist->id) && ($receptionist->id == $invoice->receptionist->id)))
                                                        selected
                                                @endif>
                                                {{ $receptionist->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                        </div>

                    </div>

                    <strong class="card-title">@lang("الشاشات")</strong>

                    <div class="card-body">
                        <div class="toolbar row mb-3">
                            <div class="col">
                                <div class="form-row">
                                    <div class="input-group">
                                        <select id="screen_id" class="form-control select2">
                                            <option value="">@lang("اختر الشاشة")</option>
                                            @foreach($screens as $screen)
                                                <option
                                                    data-id="{{$screen->id}}"
                                                    data-model="{{$screen->model}}"
                                                    data-brand="{{$screen->brand->name}}"
                                                    data-warehouse="{{$screen->warehouse->name}}"
                                                    data-buy_price="{{$screen->buy->price}}"


                                                    value="{{ $screen->id }}">( {{ $screen->code }} ) {{ $screen->brand->name }} - {{ $screen->model  }}</option>
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
                                <th>@lang("المخزن")</th>
                                <th>@lang("سعر الشراء")</th>
                                <th>@lang("سعر البيع")</th>
                                <th>@lang("العمليات")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($invoice))
                                @foreach($invoice->screens as $screen)
                                    <tr>
                                        <td>
                                            <input type="hidden"
                                                   name="screens[{{ $loop->index }}][screen_id]"
                                                   value="{{$screen->screen->id}}">
                                            <span class="screen-code">{{$screen->screen->code}}</span>
                                        </td>
                                        <td>
                                            <span class="screen-brand">{{$screen->screen->brand->name}}</span>
                                        </td>
                                        <td>
                                            <span class="screen-model">{{$screen->screen->model}}</span>
                                        </td>
                                        <td>
                                            <span class="warehouse">{{$screen->screen->warehouse->name}}</span>
                                        </td>
                                        <td>
                                            <span class="screen-buy-price">{{$screen->screen->buy->price}}</span>
                                        </td>
                                        <td>
                                            <input type="number" required value="{{$screen->screen->sale->price}}"
                                                   name="screens[{{ $loop->index }}][screen_sell_price]"
                                                   class="form-control">
                                        </td>
                                        <td>
                                        <button class="btn btn-danger removeScreen" type="button">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>


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
                        if (response !== '') {
                            customerNameInput.val(response.name);
                            customerAddressInput.val(response.address);
                            customerNameInput.prop('readonly', true);
                            customerAddressInput.prop('readonly', true);
                        }
                    },
                });
            });


            const repairScreensTableBody = $('#repairScreensTable tbody');

            $('#addScreen').click(function () {
                const selectedScreen = $('#screen_id option:selected');
                const screenId = selectedScreen.val();
                const screenCode = selectedScreen.text();
                const screenBrand = selectedScreen.data('brand');
                const screenModel = selectedScreen.data('model');
                const screenBuyPrice = selectedScreen.data('buy_price');
                const warehouse = selectedScreen.data('warehouse');

                if (!screenId) {
                    return;
                }

                const newRow = $('<tr>').html(`
        <td>
            <input type="hidden" name="screens[${repairScreensTableBody.children().length}][screen_id]" value="${screenId}">
            <span class="screen-code">${screenCode}</span>
        </td>
        <td>
            <span class="screen-brand">${screenBrand}</span>
        </td>
        <td>
            <span class="screen-model">${screenModel}</span>
        </td>
        <td>
            <span class="warehouse">${warehouse}</span>
        </td>
        <td>
            <span class="screen-buy-price">${screenBuyPrice}</span>
        </td>
        <td>
            <input type="number" required name="screens[${repairScreensTableBody.children().length}][screen_sell_price]" class="form-control">
        </td>

        <td>
            <button class="btn btn-danger removeScreen" type="button">
                <i class="fa-solid fa-trash"></i>
            </button>
        </td>
    `);

                repairScreensTableBody.append(newRow);

                selectedScreen.remove();
            });

            repairScreensTableBody.on('click', '.removeScreen', function () {
                const row = $(this).closest('tr');
                const screenId = row.find('input[type="hidden"][name*="[screen_id]"]').val();
                const screenCode = row.find('.screen-code').text();
                const screenBrand = row.find('.screen-brand').text();
                const screenModel = row.find('.screen-model').text();
                const screenBuyPrice = row.find('.screen-buy-price').text();
                const warehouseId = row.find('input[type="hidden"][name*="[screen_warehouse]"]').val();

                $('#screen_id').append($('<option>', {
                    value: screenId,
                    text: screenCode,
                    data: {
                        brand_id: screenBrand,
                        model: screenModel,
                        buy_price: screenBuyPrice,
                        werehous_id: warehouseId
                    }
                }));

                $('#screen_id').find('option[value="' + screenId + '"]').prop('selected', true);

                row.remove();
            });


            function updateTotalRepairAmount() {
                let totalRepairAmount = 0;

                repairScreensTableBody.find('tr').each(function () {
                    totalRepairAmount += 1;
                });

                $('#totalRepairAmount').text(totalRepairAmount.toFixed(2));
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
