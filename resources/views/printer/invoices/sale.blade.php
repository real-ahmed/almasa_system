<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
            <p>{{$general->phone}}</p>

        </div>
        <img src="{{ getImage(getFilePath('logoIcon').'/invoice_logo.png', '?'.time()) }}" alt="شعار الشركة">

    </div>
    <hr>
    <div class="details">
        <div class="purchase-info">
            <div>
                <div>
                    <strong>@lang('العميل') :</strong>
                    {{ $invoice->customer->name }}
                </div>
                <div>
                    <strong>@lang('تاريخ الفاتورة') :</strong>
                    {{$invoice->created_at}}
                </div>
            </div>

            <div>
                <div>
                    <strong>@lang('الرقم التسلسلي') :</strong>
                                        {{$invoice->reference_number}}

                </div>

            </div>
        </div>
    </div>
    <table class="products">
        <thead>
        <tr>
            <th>@lang('رقم')</th>
            <th>@lang("الكود")</th>
            <th>@lang("المنتج")</th>
            <th>@lang("السعر")</th>
            <th>@lang("الكمية")</th>
            <th>@lang("الاجمالي")</th>
        </tr>
        </thead>
        <tbody>
        @foreach($invoice->components as $component)
            <tr>
                <td>{{ $loop->index +1 }}</td>
                <td>{{ $component->code }}</td>
                <td>{{ $component->component->name }}</td>

                <td>{{showAmount($component->price) }} {{$general->money_sign}}</td>

                <td>{{$component->quantity}}</td>

                <td>{{showAmount($component->quantity * $component->price) }} {{$general->money_sign}}</td>



            </tr>
        @endforeach

        </tbody>
    </table>
    <div class="total">
        <strong>@lang('اجمالي الفاتورة'):</strong> {{ showAmount($invoice->total_amount) }} {{$general->money_sign}}
    </div>
</div>
</body>
</html>
