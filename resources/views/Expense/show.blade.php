<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
    <style>
        .header, .header-space {
            height: 134px!important;
        }

        .footer, .footer-space {
            height: 180px!important;
        }

        .header {
            position: fixed;
            top: 0;
            left: 10;
            right: 10;
        }
        
        .footer {
            position: fixed;
            bottom: 0; 
            left: 10;
            right: 10;
        }
        
        html, body {
            width: 210mm;
            height: 250mm;
            font-size: 12px!important;
        }
        
        @page {
            size: A4;
            margin: 0;
            margin-top: 0;
            margin-bottom: 0;
        }
        
        @media print {
            html, body {
                width: 210mm;
                height: 250mm;
            }
            
            .no-print {
                display: none;
            }
            
            .print-button {
                display: none;
            }
        }

        /* Your styles here */
        .print-button {
            display: block;
        }

        @media print {
            .print-button {
                display: none;
            }
        }

        .no-print pre {
            border: none;
        }
    </style>
</head>
<body>

    <table style="width:106%!important">
        <thead>
            <tr>
                <td>
                    <div class="header-space">&nbsp;</div>
                </td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="content" style="padding-left: 50px!important; padding-right: 50px!important">
                        <div class="row">
                            <div class="col-12">
                                <table style="white-space: nowrap;font-weight: bolder;font-size: 16px; width:100%!important">
                                    <tr>
                                        <th colspan="5" style="color: red;text-align: center;border-bottom: 2em;background: #fff;font-weight: bolder;font-size:15px!important;">Expense Report</th>
                                    </tr>
                                    @foreach($Expenses as $key => $expense)
                                    @endforeach
                                    <div class="row">
                                        <div class="col-12">
                                            <table style="white-space: nowrap;font-weight: bolder;font-size: 16px; width:100%!important">
                                                <tr>
                                                    <th style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:bolder; width:10%;font-size:12px!important;font-weight:normal">Name</th>
                                                    <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;font-size:12px!important;"> {{ $expense->user->username }}</td>
                                                    <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Date</td>
                                                    <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;padding-left:0px;font-size:12px!important;">@if($key === 0)
                                                        {{ $date }}    @endif </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Address</td>
                                                    <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important; max-width: 391px; white-space: nowrap;">
                                                        <span class="address" style="white-space: pre-wrap;">{{ $expense->user->address }}</span>
                                                    </td>
                                                    <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Branch</td>
                                                    <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">
                                                        &nbsp;{{ $expense->user->branch }}  </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Email</td>
                                                    <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:50%;padding-left:0px;font-size:12px!important;">{{ $expense->user->email }}</td>
                                                    <td style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:10%;font-size:12px!important;">Desig</td>
                                                    <td colspan="2" style="padding: 2px;background: #fff; border: 1px solid #000;font-weight:400; width:30%;font-size:12px!important;">
                                                        {{ $expense->user->custom_designation }} 
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <table border="0" cellspacing="0" cellpadding="0" style="width:100%!important">
                                        <thead>
                                            <tr>
                                                <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px; width:5%!important;font-size:12px!important;" class="text-center">S.No</th>
                                                <th style="background: #fff;border: 1px solid #000;font-weight:bolder;padding: 2px;font-size:12px!important;" class="text-left">Description</th>
                                                <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;font-size:12px!important; width:7%!important" class="text-center">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($ExpenseCategories as $category)
                                            <tr>
                                                <td style="background: #fff; border: 1px solid #000; padding: 2px; font-size: 12px!important;" class="text-center">{{$loop->iteration}}</td>
                                                <td style="background: #fff; border: 1px solid #000; padding: 2px; font-size: 12px!important;" id="categoryDiv">{{ $category->qty }}</td>
                                                <td style="background: #fff; border: 1px solid #000; text-align: right; padding: 2px; font-size: 12px!important;" class="right">{{ $category->price }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        @php
                                        // Get the first item in the collection
                                        $expense = $Expenses->first();
                                    @endphp

                                    @if($expense)
                                    <tr>
                                        <td colspan="1"></td>
                                        <td  class="text-right">Subtotal</td>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{$expense->amount}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="1"></td>
                                        <td  class="text-right">Cash Received</td>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{$expense->cashreceived}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="1"></td>
                                        <td  class="text-right">Previous Balance</td>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{$expense->pbalance}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="1"></td>
                                        <td  class="text-right">Cash in Hand</td>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{$expense->cashinhand}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="1"></td>
                                        <td  class="text-right">Remainig Balance</td>
                                        <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-right">{{$expense->remainingbalance}}</td>
                                    </tr>
                                    @endif
                                    </table>
                                    <div class="row">
                                        <div class="col-5">
                                            <img src="/signature/{{ Auth::user()->signature }}" style="width:100px; height:60px">
                                            <p>Direct All Inquiries To: <br>
                                            {{ Auth::user()->username }}<br>
                                            |Tel: {{ Auth::user()->Tel }} | Cell: {{ Auth::user()->phone_number }}|  <br>
                                            |E-Mail: sales@firesafetytrading.com.pk|
                                        </div>
                                       <div class="col-6">
                                            <br><br><br>
                                            <p style="margin-left: 140px;"><br>
                                <strong> Prepared By:</strong> <u>{{ $expense->user->username}}</u></p>

                                        </div>
                                    </div>
                            </div>
                            <div class="no-print d-flex justify-content-center" style="text-align:center!important">
                                <a href="" class="btn btn-sm btn-primary print-button" onclick="printHTML()" target="_blank" style="font-size: 14px; padding: 8px 16px;"><i class="fa fa-download"></i> Download Expense</a>
                            </div>


                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td>
                            <div class="footer-space">&nbsp;</div>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="header"><img src="{{ asset('assets/header2.jpeg') }}"  style="width: 123%; height:100%;" /></div>
             <!--style="width: 210mm!important;" -->
            <div class="footer"><img src="{{ asset('assets/footer2.jpeg') }}" style="width:122%; height:100%;"  /></div>


            <script>
                function printHTML() {
                    window.print();
                }
            </script>

        </body>
        </html>
