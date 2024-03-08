<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        @media print
        {
            html
            {
                zoom: 65%;
            }

        }
        body {
            font-family: 'Arial', sans-serif;

        }

        .container {
            width: 100%;
            background-color: #fff;

        }

        h2 {
            text-align: center;
        }

        form {
            margin-top: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 8px;
            margin-bottom: 16px;
            box-sizing: border-box;
        }

        .submit-btn {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
        }
        .inputs-filed
        {
            display: flex;
            align-items: center;


        }

        .inputs-filed input{
            border: none;
            border-bottom: 1px dotted #000;
            width: 80%;
            outline: none;
            box-shadow: none;


        }
        .inputs-filed input:focus{
            border: none;
            border-bottom: 1px dotted #000;
        }
        .inputs-filed input::placeholder{
            display: none;

        }

        .footer_sec{
            display: flex;
            align-items: center;
            justify-content: space-around;
            margin: 40px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }
        .footer input{
            border: none;
            border-bottom: 1px dotted #000;
            width: 80%;
            outline: none;
            box-shadow: none;

        }

        .footer input:focus{
            border: none;
            border-bottom: 1px dotted #000;
        }
        .footer input::placeholder{
            display: none;

        }

        .footer button{
            width: 200px;
            padding: 15px 35px;
            margin-bottom: 40px;
        }
        .footer  label {
            font-weight: bold !important;
        }


        .inputs-filed  label {
            font-weight: bold !important;
        }

        p{
            font-weight: bold;
        }

        .circle {
            width: 140px;
            height: 140px;
            margin-top: 20px;
            /* background-color: #3498db; */
            border: 2px solid  #b9141d;

            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
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


        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }


        th, td {
            padding: 10px;
            text-align: right;

        }

        .submit-btn {
            background-color: #4caf50;
            color: #fff;
            padding: 10px 15px;
            border: none;
            cursor: pointer;
            margin-top: 20px;
        }
        .input-table_one
        {
            border: none;
            border-bottom: 1px dotted #000;
            width: 100%;
            outline: none;
            box-shadow: none;

        }
        .input-table_one:focus
        {
            border: none;
            border-bottom: 1px dotted #000;
        }

        .logo {
            height: 70px;
            margin-bottom: 10px;
        }
    </style>

</head>
<body>
<div class="container">
    <div style="display: flex; align-items: center; justify-content: center; flex-direction: column;">
        <img class="logo" src="{{ getImage(getFilePath('logoIcon').'/invoice_logo.png', '?'.time()) }}" alt="">
        <h2 style="background: #b9141d; width: 200px ; padding: 15px 30px; color: #fff; border-radius: 10px; margin: 5px;"> كارت الاستلام  </h2>

        <p style="border: 3px solid #000; padding: 10px 40px; border-radius: 10px; font-weight: 500; margin-top: 5px;">تضمن الاحترافيه لخدمات الصيانه الاعمال وقطع الغيار التي تمت بالجهاز</p>
    </div>


    <div class="details">
        <div class="purchase-info">
            <div>
                <div>
                    <strong>@lang('العميل') :</strong>
                    {{ $repair->customer->name }}
                </div>
                <div>
                    <strong>@lang('تاريخ الفاتورة') :</strong>
                    {{$repair->receive_date}}
                </div>
            </div>

            <div>
                <div>
                    <strong>@lang('الرقم التسلسلي') :</strong>
                    {{$repair->reference_number}}

                </div>
                <div>
                    <strong>@lang('تاريخ التسليم المتوقع') ٍ:</strong>
                    {{$repair->expected_delivery_date }}
                </div>
            </div>
        </div>
    </div>

    <form style="margin-top: 35px;">


        <table>
            <tr style="background-color: #ec323b;">
                                <th style="color: #fff;">كود الصيانة</th>

                <th style="color: #fff;">اسم الجهاز</th>
                <th style="color: #fff;">الموديل</th>
                <th style="color: #fff;">السريال</th>
                <th style="color: #fff;">المشكلة</th>
            </tr>
            @foreach($repair->screens as $screen)
                <tr>
                                        <td>{{ $screen->code }}</td>

                    <td>{{ $screen->brand->name ?? null }}</td>
                    <td>{{ $screen->model }}</td>
                    <td>{{ $screen->serial }}</td>
                    <td>{{ $screen->issue }}</td>
                </tr>
            @endforeach


        </table>




    </form>
    <br>

    <div style=" position: absolute;
            bottom: 0px;
            left: 0px; width: 100%;" >


        <div style=" display: flex; align-items: right; justify-content: center; " >
            <div style="border: 3px solid #000; padding: 20px 40px ; display: flex; align-items: right; justify-content: center; flex-direction: column;  border-radius: 10px; font-weight: 500; margin-top: 10px;">
                <h5 style="font-size: 15px; font-weight: bold; margin: 0 !important;">عمالئنا الكرام .. لتجنب حدوث أي سوء تفاهم يرجي قراءة الشروط التالية :</h5>
                {!! $general->invoice_policy !!}


            </div>
        </div>
        <div  class="footer_sec">
            <div class="footer">
                <label for="customerSignature">توقيع العميل:</label>
                <input type="text" id="customerSignature" name="customerSignature" required>
            </div>

            <div class="footer">
                <label for="centerSeal">ختم المركز:</label>
                <!-- <input type="text" id="centerSeal" name="centerSeal" required> -->
                <div  class="circle">

                </div>

            </div>
        </div>
        <div style="display: flex;
        justify-content: space-between;">
            <p class="footer"></p>

            <p class="footer">نشكركم لاختياركم الماسة ونعدكم دائما بتقديم الافضل</p>

            <img src="{{ getImage(getFilePath('logoIcon').'/QR.jpg', '?'.time()) }}" style="width: 60px; margin: 5px;" alt="">
        </div>
        <hr style="font-weight: bold ; border-top: 5px solid #b9141d; margin: 0 !important;">
        <hr style="font-weight: bold ; border-top: 1px solid #b9141d; margin: 0 !important; ">
        <div class="footer_sec" style="margin-top: 0 !important; justify-content:space-between;">
            <p style="font-weight: normal;">{{$general->address}}</p>
            <p style="font-weight: normal;">{{$general->phone}}-{{$general->sac_phone}}</p>

        </div>

    </div>
</div>

</body>
</html>
