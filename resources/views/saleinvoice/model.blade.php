@extends("layouts.app")

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("معلومات الشراء")</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('receptionist.sale.save', $invoice->id ?? '') }}">
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
                        <div class="col-md-6">
                        </div>
                    </div>

                    <strong class="card-title">@lang("منتجات البيع")</strong>

                    <div class="card-body">
                        <div class="toolbar row mb-3">
                            <div class="col">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="input-group md-3">
                                            <select class="form-control select2" id="productSelect">
                                                @foreach($screenComponents as $product)
                                                    <option
                                                        data-code="{{ $product->code }}"
                                                        data-name="{{ $product->name }}"
                                                        data-price="{{ $product->selling_price }}"
                                                        data-quantity={{$product->instockquantity}}
                                                        value="{{ $product->id }}">
                                                        ( {{ $product->code }} ) - {{ $product->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="addProduct">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-6">
                                        <div class="md-3">
                                            <p>@lang("الكمية المتوفرة") : <strong
                                                    class="available-quantity">{{$screenComponents->first()->instockquantity}}</strong>
                                            </p>
                                            <p>@lang("السعر") : <strong
                                                    class="selling-price">{{$screenComponents->first()->selling_price}}</strong> {{$general->money_sign}}
                                            </p>

                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover" id="purchaseItemsTable">
                            <thead>
                            <tr>
                                <th>@lang("المنتج/الخدمة")</th>
                                <th>@lang("الكمية")</th>
                                <th>@lang("السعر")</th>
                                <th>@lang("الإجمالي")</th>
                                <th>@lang("العمليات")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($invoice))
                                @foreach($invoice->components as $component)
                                    <tr>

                                        <td>
                                            <input type="hidden" name="used_items[{{ $loop->index }}][id]"
                                                   value="{{$component->id}}">
                                            <input type="hidden" class="product_id"
                                                   name="used_items[{{ $loop->index }}][product_id]"
                                                   value="{{$component->screen_component_id}}">
                                            <input type="hidden" name="used_items[{{ $loop->index }}][price]"
                                                   value="{{$component->price}}">
                                            {{$component->component->name}}
                                        </td>
                                        <td>
                                            <input type="number" value="{{$component->quantity}}"
                                                   max="${availableQuantity}" min="1" required
                                                   name="used_items[{{ $loop->index }}][quantity]" class="form-control">
                                        </td>
                                        <td>
                                            <span class="price">{{$component->price}}</span>
                                        </td>
                                        <td><span
                                                class="total-price">{{$component->price * $component->quantity}}</span> {{$general->money_sign}}
                                        </td>
                                        <td>
                                            <button class="btn btn-danger removeItem" type="button">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                        <!-- Total Amount -->
                        <div>
                            <strong>@lang('اجمالي الفاتورة :')</strong>
                            <span id="totalAmount">{{$invoice->total_amount  ?? '0'}} </span> {{$general->money_sign}}
                        </div>

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
                        if (response.name!== '') {
                            customerNameInput.val(response.name);
                            customerAddressInput.val(response.address);

                            customerNameInput.prop('readonly', true);
                            customerAddressInput.prop('readonly', true);
                        }
                    },
                });
            });

            const purchaseItemsTableBody = $('#purchaseItemsTable tbody');
            const totalAmountSpan = $('#totalAmount');


            $('#productSelect').change(function () {
                const selectedProduct = $('#productSelect option:selected');

                const availableQuantity = selectedProduct.data('quantity');
                const sellingPrice = selectedProduct.data('price');

                // Display available quantity and selling price
                $('.available-quantity').text(availableQuantity);
                $('.selling-price').text(sellingPrice);
            });

            // Function to add product to the purchase items table
            $('#addProduct').click(function () {
                const selectedProduct = $('#productSelect option:selected');
                const productId = selectedProduct.val();
                const availableQuantity = selectedProduct.data('quantity');


                if (!productId || isProductAlreadyAdded(productId, purchaseItemsTableBody)) {
                    return;
                }

                const newRow = $('<tr>').html(`
                    <td>
                        <input type="hidden" class="product_id" name="used_items[${purchaseItemsTableBody.children().length}][product_id]" value="${productId}">
                        <input type="hidden" name="used_items[${purchaseItemsTableBody.children().length}][price]" value="${selectedProduct.data('price')}">
                        ${selectedProduct.data('name')}
                    </td>
                    <td>
                        <input type="number" value="1" max="${availableQuantity}" min="1" required name="used_items[${purchaseItemsTableBody.children().length}][quantity]" class="form-control">
                    </td>
                    <td>
                        <span class="price">${selectedProduct.data('price')}</span>
                    </td>
                    <td><span class="total-price">${selectedProduct.data('price')}</span> {{$general->money_sign}}</td>
                    <td>
                        <button class="btn btn-danger removeItem" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                `);

                purchaseItemsTableBody.append(newRow);
                updateTotalAmount();
            });

            // Function to check if a product is already added to the table
            function isProductAlreadyAdded(productId, tableBody) {
                return tableBody.find(`input[name^="used_items"][value="${productId}"][class = "product_id"]`).length > 0;
            }


            // Common function to handle removal of items
            $(document).on('click', '.removeItem', function () {
                $(this).closest('tr').remove();
                updateTotalAmount();
            });

            // Update total price on quantity/price change
            $(document).on('input', 'input[name^="used_items"], input[name^="service_items"]', function () {
                updateTotalPrice($(this).closest('tr'));
                updateTotalAmount();
            });

            // Function to update total price
            function updateTotalPrice(row) {
                const quantity = parseInt(row.find('input[name$="[quantity]"]').val()) || 0;
                const price = parseFloat(row.find('.price').text()) || 0;

                const totalPrice = quantity * price;
                row.find('.total-price').text(totalPrice.toFixed(2));
            }

            // Function to update total amount
            function updateTotalAmount() {
                let totalAmount = 0;

                purchaseItemsTableBody.find('tr').each(function () {
                    const totalPrice = parseFloat($(this).find('.total-price').text()) || 0;
                    totalAmount += totalPrice;
                });

                totalAmountSpan.text(totalAmount.toFixed(2));
            }
        });
    </script>
@endpush
