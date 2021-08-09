<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reservation No #{{ $info->id }}</title>
    
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, .15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table.item {
            text-align: center;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            font-weight: bold;
            padding: 10px 0;
        }

        

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /* RTL */
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .rtl table {
            text-align: right;
        }

        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset($info->vendorinfo->avatar) }}" height="50">
                                
                            </td>
                            
                            <td>
                                <strong>Invoice No: </strong>#{{ $info->id }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
           
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>

                            <td>
                               Name: {{ $info->customerinfo->name ?? '' }}<br>
                               Email: {{ $info->customerinfo->email ?? '' }}<br>
                               Phone: {{ $info->customerinfo->phone ?? '' }}
                            </td>

                            <td>
                                @if(!empty($vendor_info))
                                <strong>{{ $info->vendorinfo->name }}</strong><br>
                                {{ $vendor_info->full_address }}<br>
                                Email: {{ $vendor_info->email1 }}<br>
                                Phone: {{ $vendor_info->phone1 }}<br>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Reservation Status: <br>


                                @if($info->status == 3)
                                <div class="badge">Accepted</div>
                                @elseif($info->status == 2)
                                <div class="badge">Pending</div>
                                @else
                                <div class="badge">Declined</div>
                                @endif

                            </td>

                            <td>
                                Reservation Date: <br>
                                {{ $info->created_at->format('d-F-Y') }} 
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        <table class="item">
            <tbody>
                <tr class="heading">
                    <th class="text-left">Person</th>
                    <th class="text-left" >Date</th>      
                    <th class="text-center">Time</th>
                    <th class="text-right">Note</th>
                </tr>
                <tr>
                    <th class="text-left">{{ $info->person }}</th>
                    <th class="text-left">{{ $info->date }}</th>
                    <th class="text-center">{{ $info->time }}</th>
                    <th class="text-right">{{ $info->message }}</th>
                </tr>
            </tbody>
        </table>
    </table>
</div>
</body>
</html>