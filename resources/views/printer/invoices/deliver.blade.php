<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: Arial, sans-serif;

        }

        @media print {
            html {
                zoom: 55%;
            }

        }


        .container {
            width: 100%;
            background-color: #fff;

        }

        h2 {
            text-align: center;
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

        .input-table_one {
            border: none;
            border-bottom: 1px dotted #000;
            width: 100%;
            outline: none;
            box-shadow: none;

        }

        .input-table_one:focus {
            border: none;
            border-bottom: 1px dotted #000;
        }


        .footer_sec {
            display: flex;
            align-items: center;
            justify-content: space-around;
            margin: 40px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
        }

        .footer input {
            border: none;
            border-bottom: 1px dotted #000;
            width: 80%;
            outline: none;
            box-shadow: none;

        }

        .footer input:focus {
            border: none;
            border-bottom: 1px dotted #000;
        }

        .footer input::placeholder {
            display: none;

        }

        .footer button {
            width: 200px;
            padding: 15px 35px;
            margin-bottom: 40px;
        }

        label {
            font-weight: bold;

        }

        p {
            font-weight: bold;
            font-size: 20px;
        }

        .circle {
            width: 140px;
            height: 140px;
            margin-top: 20px;
            /* background-color: #3498db; */
            border: 2px solid #b9141d;

            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            font-size: 18px;
        }

        .logo {
            height: 70px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <div style="width: 100%;">
        <div class="header" style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; justify-content: center; width: 30%;">
                <h2 style="color: #ec323b; text-align: center;">
                    {{$general->invoice_name}}
                </h2>
            </div>
            <div>
                <img class="logo" src="{{ getImage(getFilePath('logoIcon').'/invoice_logo.png', '?'.time()) }}" alt="">
            </div>

        </div>


    </div>
    <div style="display: flex; align-items: center; justify-content: center;">
        <h2 style="background: #b9141d; width: 200px ; padding: 15px 30px; color: #fff; border-radius: 10px;"> فاتورة
            الصيانة </h2>
    </div>

    <form>
        <table>
            <tr>
                <th style="width: 100px ; background-color: #ec323b; color: #fff;">تاريخ التسليم:</th>
                <td>{{date_format($deliver->repair->created_at,'d/m/y h:i')}}</td>
            </tr>
            <tr>
                <th style="width: 100px ; background-color: #ec323b; color: #fff;">الرقم المرجعي:</th>
                <td>{{$deliver->reference_number}}</td>
            </tr>
            <tr>
                <th style="width: 100px ; background-color: #ec323b; color: #fff;">اسم العميل:</th>
                <td>{{ $deliver->repair->customer->name ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 100px ; background-color: #ec323b; color: #fff;">العنوان:</th>
                <td>{{ $deliver->repair->customer->address ?? '' }}</td>
            </tr>
            <tr>
                <th style="width: 100px ; background-color: #ec323b; color: #fff;">رقم الهاتف:</th>
                <td>{{ $deliver->repair->customer->phone ?? '' }}</td>
            </tr>
        </table>

        <table>
            <tr style="background-color: #ec323b;">
                <th style="color: #fff;">كود الصيانة</th>

                <th style="color: #fff;">اسم الجهاز</th>
                <th style="color: #fff;">الموديل</th>
                <th style="color: #fff;">السريال</th>
                <th style="color: #fff;">المشكلة</th>
                <th style="color: #fff;">حالة الصيانة</th>
            </tr>
            @foreach($deliver->repair->screens->whereNotIn('status',[4, 5]) as $screen)
                <tr>
                    <td>{{ $screen->code }}</td>

                    <td>{{ $screen->brand->name }}</td>
                    <td>{{ $screen->model }}</td>
                    <td>{{ $screen->serial }}</td>
                    <td>

                            @foreach($screen->services as $service)
                                <span>{{ $service->service->name }}</span>
                            @endforeach


                    </td>
                    <td>{{$screen->StatusInvoice}}</td>

                </tr>
            @endforeach
        </table>


        <!-- <div class="footer">
            <button type="submit" class="submit-btn">إرسال</button>
        </div> -->

    </form>

    <div style=" position: absolute;
            bottom: 0px;
            left: 0px; width: 100%;">
        <table>

            <tr>
                <td style=" width: 200px ; color: #ec323b; "><label for="notes">الملاحظات:</label></td>
                <td>{{$deliver->note}}</td>
            </tr>
            <tr>
                <td style="width: 200px ;color: #ec323b; "><label for="invoiceAmount">قيمة الفاتورة:</label></td>
                <td>                    <span
                        id="totalAmount">{{$deliver->total_amount  ?showAmount($deliver->total_amount ,0): '0'}} </span> {{$general->money_sign}}
                </td>
            </tr>
            <tr>
                <td style="width: 200px ;color: #ec323b; "><label for="advancePayment">العربون:</label></td>
                <td>                    <span
                        id="totalAmount">{{$deliver->repair->paid  ?showAmount($deliver->repair->paid ,0): '0'}} </span> {{$general->money_sign}}</td>
            </tr>
            <tr>
                <td style="width: 200px ;color: #ec323b; "><label for="remainingAmount">المتبقي:</label></td>
                <td>
                    <span
                        id="totalAmount">{{$deliver->total_amount  ?showAmount($deliver->total_amount - ($deliver->received_amount+$deliver->repair->paid) ,0): '0'}} </span> {{$general->money_sign}}
                </td>
            </tr>
            <tr>
                <td style="width: 200px ;color: #ec323b; "><label for="paidAmount">المبلغ المدفوع:</label></td>
                <td>                   <span
                        id="totalAmount">{{$deliver->received_amount  ?showAmount($deliver->received_amount,0 ): '0'}} </span> {{$general->money_sign}}
                </td>
            </tr>
        </table>

        <div class="footer_sec">
            <div class="footer">
                <label for="customerSignature">توقيع العميل:</label>
                <input type="text" id="customerSignature" name="customerSignature" required>
            </div>

            <div class="footer">
                <label for="centerSeal">ختم المركز:</label>
                <!-- <input type="text" id="centerSeal" name="centerSeal" required> -->
                <div class="circle">

                </div>

            </div>
        </div>

        <div style="      display: flex;
        margin: 0 !important;
    flex-direction: column;
    align-items: center; text-align: center;">
            <p class="footer"
               style="margin: 0 !important; text-align: center;">
                {{$general->insurance_term}}
            </p>

            <p class="footer"
               style=" margin: 0 !important; text-align: center;">
                نشكركم لختياركم الماسة ونعدكم دائما بتقديم الافضل </p>


        </div>

        <div class="footer_sec" style="margin-bottom: -1px !important; justify-content:space-between;">
            <p style="font-size: 15px; font-weight: normal;">برجاء مراجعة الشروط خلف الفاتورة</p>
            <img src="{{ getImage(getFilePath('logoIcon').'/QR.jpg', '?'.time()) }}" style="width: 60px; margin: 5px;" alt="">

        </div>
        <hr style="font-weight: bold ; border-top: 5px solid #b9141d; margin: 0 !important;">
        <hr style="font-weight: bold ; border-top: 1px solid #b9141d; margin: 0 !important; ">
        <div class="footer_sec"
             style="margin-top: 0 !important; margin-bottom: 0 !important; justify-content:space-between;">
            <p style="font-size: 20px; font-weight: normal;">{{$general->address}}</p>
            <p style="font-size: 20px; font-weight: normal;">{{$general->phone}}-{{$general->sac_phone}}</p>
        </div>
    </div>
</div>

</body>
</html>
