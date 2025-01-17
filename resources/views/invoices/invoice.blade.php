<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Faktura</title>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <style>
        @page {
            size: a4;
            margin: 0px;
        }

        p,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0px;
        }

        body {
            color: #666;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 1.6em;
            overflow-x: hidden;
            background-color: #f5f6fa;
        }

        .ll-invoice-in {
            padding: 0px 50px 30px;
            max-width: 1120px;
            margin: 0px auto;
            background: white;
            overflow: hidden;
            min-height: 1092px;
            position: relative;
        }

        .ll-invoice-head {
            position: relative;
            height: 110px;
            margin-bottom: 15px;
        }

        .ll-invoice-head .ll-invoice-logo {
            float: left;
            width: 30%;
            padding: 35px 0px;
        }

        .ll-invoice-head .ll-invoice-title {
            float: left;
            width: 70%;
            font-size: 50px;
            line-height: 1em;
            color: white;
            text-transform: uppercase;
            text-align: right;
            position: relative;
            z-index: 99;
            padding: 30px 0px;
        }

        .ll-invoice-head .ll-invoice-bg-shape {
            position: absolute;
            height: 100%;
            width: 80%;
            -webkit-transform: skewX(35deg);
            transform: skewX(35deg);
            top: 0px;
            right: -95px;
            overflow: hidden;
            background-color: #FBBF24;
        }

        .ll-invoice-info {
            position: relative;
            height: 30px;
            margin-bottom: 25px;
            vertical-align: middle;
        }

        .ll-invoice-info .ll-invoice-info_left {
            width: 30%;
            float: left;
            padding: 4px 0px;
            vertical-align: middle;
        }

        .ll-invoice-info_right {
            float: left;
            width: 35%;
            display: table;
            table-layout: fixed;
            padding-top: 2px; 
            color: white;
            position: relative;
            z-index: 999;
            vertical-align: middle;
        }

        .ll-invoice-info_right div {
            display: table-cell;
            text-align: right;
            padding: 0px;
            color: white !important;
            vertical-align: middle;
        }

        .ll-invoice-info_right div span,
        .ll-invoice-info_right div b {
            vertical-align: middle;
        }

        .ll-invoice-info .ll-invoice-info_shape {
            margin-right: 0;
            border-radius: 0;
            -webkit-transform: skewX(35deg);
            transform: skewX(35deg);
            position: absolute;
            height: 30px;
            width: 70%;
            right: -80px;
            overflow: hidden;
            border: none;
            background-color: #FBBF24;
        }

        .ll-invoice-pay-to {
            display: table;
            table-layout: fixed;
            width: 100%;
        }

        .ll-invoice-pay-to div {
            display: table-cell;
        }

        .ll-invoice-pay-to .right-side {
            text-align: right;
        }

        .ll-invoice-main-table table {
            width: 100%;
            caption-side: bottom;
            border-collapse: collapse;
            vertical-align: middle;
        }

        .ll-invoice-main-table table th {
            padding: 10px 15px;
            text-align: left;
            color: white;
            vertical-align: middle;
        }

        .ll-invoice-main-table table th,
        .ll-invoice-main-table table td,
        .ll-invoice-footer-table .right-side table td {
            padding-bottom: 12px;
        }

        .ll-invoice-main-table table td {
            padding: 10px 15px;
            line-height: 1.55em;
            text-align: left;
            border-bottom: 1px solid #dbdfea;
            vertical-align: middle;
        }

        .ll-invoice-main-table table thead {
            vertical-align: middle;
        }

        .ll-invoice-main-table table thead tr {
            background-color: #FBBF24;
            vertical-align: middle;
        }

        .ll-invoice-main-table table tbody {
            vertical-align: middle;
        }

        .ll-invoice-footer-table {
            display: table;
            table-layout: fixed;
            width: 100%;
            margin-bottom: 30px;
        }

        .ll-invoice-footer-table .left-side {
            width: 58%;
            padding: 10px 15px;
            display: table-cell;
        }

        .ll-invoice-footer-table .right-side {
            display: table-cell;
            width: 40%;
        }

        .ll-invoice-footer-table .right-side table {
            width: 100%;
            caption-side: bottom;
            border-collapse: collapse;
        }

        .ll-invoice-footer-table .right-side table tr {
            vertical-align: middle;
        }

        .ll-invoice-footer-table .right-side table td {
            padding: 10px 15px;
            line-height: 1.55em;
            vertical-align: middle;
        }

        .w-15 {
            width: 25%;
        }

        .w-33 {
            width: 33.33%;
        }

        .w-16 {
            width: 16.66%;
        }

        .w-8 {
            width: 8.33%;
        }

        .text-right {
            text-align: right !important;
        }
    </style>
</head>
<body>
    <div class="ll-invoice-wrapper" id="download-section">
        <div class="ll-invoice-in">
            <div class="ll-invoice-head">
                <div class="ll-invoice-logo">
                    {{ config('app.name') }}
                </div>
                <div class="ll-invoice-title">
                    Faktura
                </div>
                <div class="ll-invoice-bg-shape"></div>
            </div>
            <div class="ll-invoice-info">
                <div class="ll-invoice-info_left"></div>
                <div class="ll-invoice-info_right">
                    <div><span>Data:</span>
                        <b>{{ $invoice->received_at->format('d.m.Y') }}</b>
                    </div>
                </div>
                <div class="ll-invoice-info_shape"></div>
            </div>
            <div class="ll-invoice-pay-to">
                <div class="left-side">
                    <p style="margin-bottom: 2px; color: #111;">
                        <span>Numer faktury:</span> <b>#{{ $invoice->id }}</b> <br>
                        <b>Wystawca:</b>
                    </p>
                    <p style="margin-bottom: 15px;">
                        {{ config('app.name') }} <br>
                        {{ config('app.email') }} <br>
                        <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
                    </p>
                </div>
                <div class="right-side">
                    <p style="margin-bottom: 2px; color: #111;">
                        <b>Nabywca:</b>
                    </p>
                    <p style="margin-bottom: 15px;">
                        {{ $invoice->user->name }} <br>
                        {{ $invoice->user->email }} <br>
                    </p>
                </div>
            </div>
            <div class="ll-invoice-table-wrap">
                <div class="ll-invoice-main-table">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 70%;">Opis</th>
                                <th style="width: 30%;" class="text-right">Kwota</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Usługa</td>
                                <td class="text-right">PLN {{ number_format($invoice->amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="ll-invoice-footer-table">
                    <div class="right-side">
                        <table>
                            <tbody>
                                <tr style="background: #f5f6fa;">
                                    <td style="color: #111; font-weight: 700;">Suma netto</td>
                                    <td class="text-right" style="color: #111; font-weight: 700;">
                                        PLN {{ number_format($invoice->amount, 2) }}
                                    </td>
                                </tr>
                                <tr style="background-color: #FBBF24; color: white;">
                                    <td style="font-size: 16px; font-weight: 700;">Suma brutto</td>
                                    <td style="font-size: 16px; font-weight: 700;" class="text-right">
                                        PLN {{ number_format($invoice->amount, 2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="position: absolute; bottom: 30px; left:50px; right: 50px; text-align: center;">
                <hr style="background: #dbdfea; margin-bottom: 15px;">
                <p style="margin-bottom: 2px"><b style="color:#111">Status: {{ $invoice->status }}</b></p>
            </div>
        </div>
    </div>
</body>
</html>