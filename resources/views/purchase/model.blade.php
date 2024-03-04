@extends("layouts.app")

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("معلومات الشراء")</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('warehouse.employee.purchase.save', $purchase->id ?? '') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="supplier">@lang("المورد")</label>
                                <select id="supplier" name="supplier_id" class="form-control">
                                    @foreach($suppliers as $supplier)
                                        <option
                                            value="{{ $supplier->id }}" {{ (isset($purchase) && $purchase->supplier_id == $supplier->id) ? 'selected' : '' }}>{{ $supplier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label for="warehouse">@lang("المخزن")</label>
                                <select id="warehouse" name="warehouse_id" class="form-control">
                                    @foreach($warehouses as $warehouse)
                                        <option
                                            value="{{ $warehouse->id }}" {{ (isset($purchase) && $purchase->warehouse_id == $warehouse->id) ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->isadmin)
                    <strong class="card-title">@lang("معلومات الفاتورة")</strong>

                    <div class="row card-body">
                        <div class="col-md-6">

                            <div class="form-group mb-3"  >
                                <label for="receive_date" class="col-form-label">@lang('موظف الاستقبال')</label>
                                <div class="input-group">
                                    <select name="warehouse_employee_id" class="form-control">
                                        <option value="">@lang("اختار موظف")</option>
                                        @foreach($warehouseEmployees as $employee)
                                            <option value="{{ $employee->id }}"
                                                    @if($employee->id == auth()->user()->id || (isset($purchase->warehouseEmployee->id) && ($employee->id == $purchase->warehouseEmployee->id)))
                                                        selected
                                                @endif>
                                                {{ $employee->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>




                        </div>

                    </div>
                    @endif

                    <strong class="card-title">@lang("منتجات الشراء")</strong>

                    <div class="card-body">
                        <div class="toolbar row mb-3">
                            <div class="col">
                                <div class="form-row">
                                    <div class="input-group col-md-5">
                                        <select class="form-control select2" id="productSelect">
                                            @foreach($screenComponents as $product)
                                                <option
                                                    data-code="{{ $product->code }}" data-name="{{ $product->name }}"
                                                    data-price="{{ $product->price }}"
                                                    value="{{ $product->id }}">
                                                   {{ $product->category->name }} - {{ $product->subcategory->name }} - {{ $product->brand->name }} -{{ $product->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button" id="addProduct">
                                                <i class="fa-solid fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover" id="purchaseItemsTable">
                            <thead>
                            <tr>
                                <th>@lang("المنتج")</th>
                                <th>@lang("الكود")</th>
                                <th>@lang("الكمية")</th>
                                <th>@lang("السعر")</th>
                                <th>@lang("الإجمالي")</th>
                                <th>@lang("العمليات")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($purchase))
                                @foreach($purchase->items as $item)
                                    <tr>




                                        <td>
                                            <input type="hidden" name="purchase_items[{{ $loop->index }}][id]"
                                                   value="{{ $item->id }}">
                                            <input type="hidden" name="purchase_items[{{ $loop->index }}][product_id]"
                                                   value="{{ $item->screen_component_id }}">
                                            {{ $item->product->name }}
                                        </td>
                                        <td>
                                            <span class="product-code">{{ $item->product->code }}</span>
                                        </td>
                                        <td>
                                            <input required type="number" name="purchase_items[{{ $loop->index }}][quantity]"
                                                   value="{{ $item->quantity }}" class="form-control">
                                        </td>
                                        <td>
                                            <input required type="number" step="any" name="purchase_items[{{ $loop->index }}][price]"
                                                   value="{{ $item->price }}" class="form-control">


                                        </td>
                                        <td>
                                            <span class="total-price">{{ showAmount($item->quantity * $item->price) }} </span>{{$general->money_sign}}
                                        </td>
                                        <td>
                                            <button class="btn btn-danger removeProduct" type="button">
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
                            <span id="totalAmount">{{$purchase->total_amount  ?? '0'}} </span> {{$general->money_sign}}
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
            const purchaseItemsTableBody = $('#purchaseItemsTable tbody');
            const totalAmountSpan = $('#totalAmount');

            $('#addProduct').click(function () {
                const selectedProduct = $('#productSelect option:selected');
                const productId = selectedProduct.val();

                if (!productId) {
                    return;
                }

                const newRow = $('<tr>').html(`
                    <td>
                        <input type="hidden" name="purchase_items[${purchaseItemsTableBody.children().length}][product_id]" value="${productId}">
                        ${selectedProduct.data('name')}
                    </td>
                    <td>
                        <span class="product-code">${selectedProduct.data('code')}</span>
                    </td>
                    <td>
                        <input type="number" required name="purchase_items[${purchaseItemsTableBody.children().length}][quantity]" class="form-control">
                    </td>
                    <td>
                        <input type="number" step="any" required name="purchase_items[${purchaseItemsTableBody.children().length}][price]" value="${selectedProduct.data('price')}" class="form-control input-phoneeg">
                    </td>
                    <td><span class="total-price">0</span> {{$general->money_sign}}</td>
                    <td>
                        <button class="btn btn-danger removeProduct" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                `);

                purchaseItemsTableBody.append(newRow);
            });

            purchaseItemsTableBody.on('click', '.removeProduct', function () {
                $(this).closest('tr').remove();
                updateTotalAmount();
            });

            // Update total price on quantity/price change
            purchaseItemsTableBody.on('input', 'input[name^="purchase_items"]', function () {
                updateTotalPrice($(this).closest('tr'));
                updateTotalAmount();
            });

            // Function to update total price
            function updateTotalPrice(row) {
                const quantity = parseInt(row.find('input[name$="[quantity]"]').val()) || 0;
                const price = parseFloat(row.find('input[name$="[price]"]').val()) || 0;

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
