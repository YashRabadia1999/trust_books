<!DOCTYPE html>
<html lang="en" dir="{{ $settings['site_rtl'] == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ \Workdo\DrivingSchool\Entities\DrivingInvoice::invoiceNumberFormat($drivinginvoice->invoice_id, $drivinginvoice->created_by, $drivinginvoice->workspace) }}
        |
        {{ !empty(company_setting('title_text', $drivinginvoice->created_by, $drivinginvoice->workspace)) ? company_setting('title_text', $drivinginvoice->created_by, $drivinginvoice->workspace) : (!empty(admin_setting('title_text')) ? admin_setting('title_text') : 'WorkDo') }}
    </title>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <style type="text/css">
        :root {
            --theme-color: {{ $color }};
            --white: #ffffff;
            --black: #000000;
        }

        body {
            font-family: 'Lato', sans-serif;
        }

        p,
        li,
        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
            line-height: 1.5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr th {
            padding: 0.75rem;
            text-align: left;
        }

        table tr td {
            padding: 0.75rem;
            text-align: left;
        }

        table th small {
            display: block;
            font-size: 12px;
        }

        .invoice-preview-main {
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            background: #ffff;
            box-shadow: 0 0 10px #ddd;
        }

        .invoice-logo {
            max-width: 200px;
            width: 100%;
        }

        .invoice-header table td {
            padding: 15px 30px;
        }

        .text-right {
            text-align: right;
        }

        .no-space tr td {
            padding: 0;
            white-space: nowrap;
        }

        .vertical-align-top td {
            vertical-align: top;
        }

        .view-qrcode {
            max-width: 139px;
            height: 139px;
            width: 100%;
            margin-left: auto;
            margin-top: 15px;
            background: var(--white);
            padding: 13px;
            border-radius: 10px;
        }

        .view-qrcode img {
            width: 100%;
            height: 100%;
        }

        .invoice-body {
            padding: 30px 25px 0;
        }

        table.add-border tr {
            border-top: 1px solid var(--theme-color);
        }

        tfoot tr:first-of-type {
            border-bottom: 1px solid var(--theme-color);
        }

        .total-table tr:first-of-type td {
            padding-top: 0;
        }

        .total-table tr:first-of-type {
            border-top: 0;
        }

        .sub-total {
            padding-right: 0;
            padding-left: 0;
        }

        .border-0 {
            border: none !important;
        }

        .invoice-summary td,
        .invoice-summary th {
            font-size: 13px;
            font-weight: 600;
        }

        .total-table td:last-of-type {
            width: 146px;
        }

        .invoice-footer {
            padding: 15px 20px;
        }

        .itm-description td {
            padding-top: 0;
        }

        html[dir="rtl"] table tr td,
        html[dir="rtl"] table tr th {
            text-align: right;
        }

        html[dir="rtl"] .text-right {
            text-align: left;
        }

        html[dir="rtl"] .view-qrcode {
            margin-left: 0;
            margin-right: auto;
        }

        p:not(:last-of-type) {
            margin-bottom: 15px;
        }

        .invoice-summary p {
            margin-bottom: 0;
        }

        .wid-75 {
            width: 75px;
        }
    </style>
</head>

<body>
    <div class="invoice-preview-main" id="boxes">
        <div class="invoice-header" style="background-color: var(--theme-color); color: {{ $font_color }};">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <img class="invoice-logo" src="{{ $img }}" alt="">
                        </td>
                        <td class="text-right">
                            <h3 style="text-transform: uppercase; font-size: 40px; font-weight: bold; ">
                                {{ __('INVOICE') }}</h3>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="vertical-align-top">
                <tbody>
                    <tr>
                        <td style="width: 60%;">
                            <table class="no-space" style="text-align-last: end;">
                                <tbody>
                                    <tr>
                                        <td>{{ __('Number: ') }}</td>
                                        <td class="float-right">
                                            {{ \Workdo\DrivingSchool\Entities\DrivingInvoice::invoiceNumberFormat($drivinginvoice->invoice_id, $drivinginvoice->created_by, $drivinginvoice->workspace) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Issue Date:') }}</td>
                                        <td class="float-right">
                                            {{ company_date_formate($drivinginvoice->issue_date, $drivinginvoice->created_by, $drivinginvoice->workspace) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Due Date') }}:</td>
                                        <td class="float-right">
                                            {{ company_date_formate($drivinginvoice->due_date, $drivinginvoice->created_by, $drivinginvoice->workspace) }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <table class="no-space">
                                <tbody>
                                    <tr>
                                        <td colspan="2" style="padding-top: 15px;">
                                            @if (!empty($students->name) && !empty($students->address))
                                                <div class="row">
                                                    <div class="col">
                                                        <small class="font-style">
                                                            <strong>{{ __('Student') }} :</strong><br>
                                                            {{ !empty($students->name) ? $students->name : '' }}<br>
                                                            {{ !empty($students->address) ? $students->address : '' }}<br>
                                                            {{ !empty($students->city) ? $students->city . ' ,' : '' }}
                                                            {{ !empty($students->state) ? $students->state . ' ,' : '' }}
                                                            {{ !empty($students->country) ? $students->country : '' }}<br>
                                                            {{ !empty($students->pin_code) ? $students->pin_code : '' }}<br>
                                                            {{ !empty($students->mobile_no) ? $students->mobile_no : '' }}<br>
                                                        </small>
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="col">
                                            <div class="float-end view-qrcode">
                                                {!! DNS2D::getBarcodeHTML(
                                                    route('drivinginvoice.show', \Illuminate\Support\Facades\Crypt::encrypt($drivinginvoice->id)),
                                                    'QRCODE',
                                                    2,
                                                    2,
                                                ) !!}
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="invoice-body">
            <table class="add-border invoice-summary" style="margin-top: 30px;">
                <thead style="background-color: var(--theme-color);color: {{ $font_color }};">
                    <tr>
                        <th>*</th>
                        <th>{{ __('Class') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Fees') }}</th>
                        <th>{{ __('Amount') }}</th>
                    </tr>
                </thead>
                @php
                    $i = 1;
                @endphp
                <tbody>
                    @if (isset($items))
                        @foreach ($items as $key => $iteam)
                            @php
                                $invoiceclass = $iteam->driving_class_id;
                                $data = Workdo\DrivingSchool\Entities\DrivingClass::where('id', $invoiceclass)->get();
                            @endphp

                            @foreach ($data as $key => $datas)
                                <tr>
                                    <td>{{ $i++ }}</td>
                                    <td>{{ $datas->name }}</td>
                                    <td>{{ $iteam->quantity }}</td>
                                    <td>{{ $datas->fees }}</td>
                                    <td>{{ $iteam->quantity * $datas->fees }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <p>-</p>
                            </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        @php
                            $colspan = 3;
                        @endphp
                    <tr>
                        <td colspan="{{ $colspan }}"></td>
                        <td><b>{{ __('Total Amount') }}</b></td>
                        <td>
                            {{ currency_format_with_sym($drivinginvoice->getTotal(), $drivinginvoice->created_by, $drivinginvoice->workspace) }}
                        </td>
                    </tr>
                    @if (!empty($amount))
                        <tr>
                            <td colspan="{{ $colspan }}"></td>
                            <td><b>{{ __('Paid') }}</b></td>
                            <td>
                                {{ currency_format_with_sym($amount, $drivinginvoice->created_by, $drivinginvoice->workspace) }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td colspan="{{ $colspan }}"></td>
                        <td><b>{{ __('Due') }}</b></td>
                        <td>
                            {{ currency_format_with_sym($dueAmount, $drivinginvoice->created_by, $drivinginvoice->workspace) }}
                        </td>
                    </tr>
                    </tr>
                </tfoot>
            </table>
            <div class="invoice-footer">
                <p> {{ $settings['footer_title'] }} <br>
                    {{ $settings['footer_notes'] }} </p>
            </div>
        </div>
    </div>
    @if (!isset($preview))
        @include('driving-school::invoice.script');
    @endif
</body>

</html>
