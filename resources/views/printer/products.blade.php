<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @media print
        {
            html
            {
                zoom: 70%;
            }

        }
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
        }

        .invoice {
            width: 80%;
            margin: 50px auto;

            padding: 20px;
            direction: rtl;
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .header img {
            margin: 0;
            height: 50px;
            margin-left: 20px;
        }

        .company-info h3 {
            margin: 0;
        }

        .purchase-info {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .details p {
            margin: 5px 0;
        }

        .products {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .products th, .products td {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
        }

        .total {
            margin-top: 20px;
            text-align: left;
        }

    </style>
    <title>الفاتورة</title>
</head>
<body>
<div class="invoice">
    <div class="header">
        <div class="company-info">
            <h3>{{$general->invoice_name}}</h3>
            <p>{{$general->address}}</p>
            <p>{{$general->phone}}-{{$general->sac_phone}}</p>

        </div>
        <img src="{{ getImage(getFilePath('logoIcon').'/invoice_logo.png', '?'.time()) }}" alt="شعار الشركة">

    </div>
    <hr>
    <table class="products">
        <thead>
        <tr>
            <th>@lang('رقم')</th>
            <th>@lang('المنتج')</th>
            <th>@lang("البراند")</th>
            <th>@lang("الفئة")</th>
            <th>@lang("الفئة الفرعية")</th>
            <th>@lang('الكود')</th>

            <th>@lang('الكمية')</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $loop->index +1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{$product->brand->name??'-'}}</td>
                <td>{{$product->category->name}}</td>
                <td>{{$product->subcategory->name ?? "-"}}</td>
                <td>{{ $product->code }}</td>
                <td>{{ $product->instockquantity }}</td>

            </tr>
        @endforeach

        </tbody>
    </table>

</div>
</body>
</html>
