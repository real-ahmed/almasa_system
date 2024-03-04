@extends("layouts.app")

@section('panel')
    <div class="my-4">
        <div class="card shadow mb-4">
            <div class="card-header">
                <strong class="card-title">@lang("معلومات الشراء")</strong>
            </div>
            <div class="card-body">
                <form method="post" action="{{ route('repair.request.save', $screen->id ?? '') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>@lang('البراند') : </strong>{{$screen->brand->name}}
                            </div>

                            <div class="mb-3">
                                <strong>@lang('العميل') : </strong>{{$screen->repairs->first()->customer->name}}
                            </div>

                            <div class="mb-3">
                                <strong>@lang('المخزن') : </strong>{{$screen->warehouse->name}}
                            </div>

                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>@lang('الموديل') : </strong>{{$screen->model}}
                            </div>
                            <div class="mb-3">
                                <strong>@lang('الرسبشن') : </strong>{{$screen->repairs->first()->receptionist->name}}
                            </div>


                        </div>
                    </div>


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
                            @if(isset($screen))
                                @foreach($screen->components as $component)
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
                                                   max="{{$component->quantity + $component->component->instockquantity}}" min="1" required
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


                    </div>


                    <div class="card-body">
                        <div class="toolbar row mb-3">
                            <div class="col">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="input-group col-md-12">
                                            <select class="form-control select2" id="servicesSelect">
                                                @foreach($services as $service)
                                                    <option
                                                        data-name="{{ $service->name }}"
                                                        data-price="{{ $service->price }}"
                                                        value="{{ $service->id }}">
                                                        {{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                            <select class="form-control select2" id="engineer_maintenance">
                                                @foreach($engineers as $engineer)
                                                    <option
                                                        data-name="{{ $engineer->name }}"

                                                        value="{{ $engineer->id }}">{{ $engineer->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button" id="addService">
                                                    <i class="fa-solid fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover" id="serviceItemsTable">
                            <thead>
                            <tr>
                                <th>@lang("المنتج/الخدمة")</th>
                                <th>@lang("مهندس الصيانة")</th>
                                <th>@lang("السعر")</th>

                                <th>@lang("العمليات")</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($screen))
                                @foreach($screen->services as $service)
                                    <tr>


                                        <td>
                                            <input type="hidden" name="service_items[{{ $loop->index }}][id]"
                                                   value="{{$service->id}}">
                                            <input type="hidden" name="service_items[{{ $loop->index }}][service_id]"
                                                   value="{{$service->repair_type_id}}">
                                            <input type="hidden" name="service_items[{{ $loop->index }}][engineer_maintenance]"
                                                   value="{{$service->engineer_maintenance->id}}">

                                            {{$service->service->name}}
                                        </td>
                                        <td>
                                            {{$service->engineer_maintenance->name}}
                                        </td>
                                        <td>
                                            <input type="number" class="form-control total-price" min="1" name="service_items[{{ $loop->index }}][price]" value="{{$service->price}}">
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
                            <span id="totalAmount">{{$screen->repair_amount ?? '0'}} </span> {{$general->money_sign}}
                        </div>

                    </div>


                    <button value="0" name='save_type' type="submit" class="btn btn-primary">
                        @lang("حفظ")
                    </button>
                    @if($screen->status == 0)
                        <button value="1" name='save_type' type="submit" class="btn btn-success">
                            @lang("حفظ للتسلييم")
                        </button>

                        <button value="3" name='save_type' type="submit" class="btn btn-danger">
                            @lang("حفظ للتسلييم تالف")
                        </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function () {
            const purchaseItemsTableBody = $('#purchaseItemsTable tbody');
            const serviceItemsTableBody = $('#serviceItemsTable tbody');
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
                console.log(availableQuantity);


                if (!productId || isProductAlreadyAdded(productId, purchaseItemsTableBody ) || availableQuantity == 0) {
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

            // Function to add service to the service items table
            $('#addService').click(function () {
                const selectedService = $('#servicesSelect option:selected');
                const selectedEngineer = $('#engineer_maintenance option:selected');
                const serviceId = selectedService.val();
                const engineerId = selectedEngineer.val();

                if (!serviceId) {
                    return;
                }

                const newRow = $('<tr>').html(`
                    <td>
                        <input type="hidden" name="service_items[${serviceItemsTableBody.children().length}][service_id]" value="${serviceId}">
                        <input type="hidden" name="service_items[${serviceItemsTableBody.children().length}][engineer_maintenance]" value="${engineerId}">

                        ${selectedService.data('name')}
                    </td>
<td>
${selectedEngineer.data('name')}

</td>
                    <td>

                 <input type="number" class="form-control total-price" min="1" name="service_items[${serviceItemsTableBody.children().length}][price]" value="${selectedService.data('price')}">


                </td>
                    <td>
                        <button class="btn btn-danger removeItem" type="button">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                `);

                serviceItemsTableBody.append(newRow);
                updateTotalAmount();
            });

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

                serviceItemsTableBody.find('tr').each(function () {
                    const totalPrice = parseFloat($(this).find('.total-price').val()) || 0;
                    totalAmount += totalPrice;
                });

                totalAmountSpan.text(totalAmount.toFixed(2));
            }
        });
    </script>
@endpush



