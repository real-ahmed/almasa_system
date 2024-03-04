<!-- resources/views/purchase/invoice.blade.php -->

<div class="invoice-details">

    <div class="row mb-3">
        <div class="col-md-6">
            <strong>@lang('الموزع') :</strong> {{ $purchase->supplier->name }}
        </div>
        <div class="col-md-6">
            <strong>@lang('المخزن') :</strong> {{ $purchase->warehouse->name }}
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-6">
            <strong>@lang('التاريخ') :</strong> {{date_format($purchase->created_at,'d/m/y h:i')}}
        </div>
        <div class="col-md-6">
            <strong>@lang('الرقم المرجعي') :</strong> {{$purchase->reference_number}}
        </div>
    </div>

    <table class="table table-hover">
        <thead>
        <tr>
            <th>@lang('المنتج')</th>
            <th>@lang('الكود')</th>
                                    <th>@lang("البراند")</th>
                        <th>@lang("الفئة")</th>
                        <th>@lang("الفئة الفرعية")</th>
            <th>@lang('الكمية')</th>
            <th>@lang('السعر')</th>
            <th>@lang('الاجمالي')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($purchase->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->product->code }}</td>
                <td>{{ $item->product->brand->name }}</td>
                <td>{{ $item->product->category->name }}</td>
                <td>{{ $item->product->subcategory->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ $item->price }} {{$general->money_sign}}</td>
                <td>{{ $item->quantity * $item->price }} {{$general->money_sign}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="total-amount">
        <strong>@lang('اجمالي الفاتورة'):</strong> {{ $purchase->total_amount }} {{$general->money_sign}}
    </div>
</div>
