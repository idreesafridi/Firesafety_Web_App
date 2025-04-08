<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
            .header, .header-space
            {
                /*height: 162px!important;*/
                height: 134px!important;
            }

            .footer, .footer-space
            {
                height: 180px!important;
                /*height: 217px!important;*/
            }

            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
            }
            .footer {
                position: fixed;
                bottom: 0; 
                left: 0;
                right: 0;
            }
            html, body {
                width: 210mm;
                height: 250mm; /* height: 297mm; */
                font-size:12px!important;
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
                    height: 250mm; /* height: 297mm; */
                }
            }
            #descriptionDiv p{
                margin-bottom: 0!important;
            }
        </style>
    </head>
<body>
@foreach($branches as $branch)

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
		                            
		                           
		                            <tr>
			                            <td style="padding: 2px;background: #fff; border: 0px solid #000;font-weight:400; width:30%;font-size:12px!important;">
		                                	Date:
		                                </td>
		                            </tr>
		                            
		                            
		                            
		                            
			                    </table>
                            </div>
                        </div>
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
                                @foreach($Expenses->where('branch_id', $branch->id) as $expense)
                                <tr>
                                    <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{ $loop->iteration}}</td>
                                    <td style="background: #fff; border: 1px solid #000;padding: 2px;;font-size:12px!important;" id="descriptionDiv">{{$expense->description}}</td>                              
                                    <td style="background: #fff; border: 1px solid #000;font-weight:400; text-align:right;padding: 2px;;font-size:12px!important;" class="text-center">{{$expense->amount}}</td>
                                </tr>
                                @endforeach
                              
                              </tbody>
                              <tr>
                                <td colspan="1"></td>
                                    <td  class="text-right">Subtotal</td>
                                    <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-center">1000</td>
                                </tr>
                                <tr>
                                <td colspan="1"></td>
                                    <td  class="text-right">Cash Received</td>
                                    <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-center">1000</td>
                                </tr>
                                <tr>
                                <td colspan="1"></td>
                                    <td  class="text-right">Previous Balance</td>
                                    <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-center">1000</td>
                                </tr>
                                <tr>
                                <td colspan="1"></td>
                                    <td  class="text-right">Cash in Hand</td>
                                    <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-center">1000</td>
                                </tr>
                                <tr>
                                <td colspan="1"></td>
                                    <td  class="text-right">Remainig Balance</td>
                                    <td style="background: #fff; border: 1px solid #000;padding-left: 5.5em;;padding: 2px;;font-size:12px!important;"  class="text-center">1000</td>
                                </tr>

                            </table>
                            
                            <div class="row">
                                <div class="col-5">
                                    <img src="/signature/{{ Auth::user()->signature }}" style="width:100px; height:60px">
    		                        <p>Direct All Inquiries To: <br>
                                    {{ Auth::user()->username }}<br>
                                    |Tel: {{ Auth::user()->Tel }} | Cell: {{ Auth::user()->phone_number }}|  <br>
			                        |E-Mail: sales@firesafetytrading.com.pk|
                                </div>
                                <div class="col-1"></div>
                                <div class="col-6">
                                    <br><br><br>
                                    <p> <br>
                                    _____________________________________________________<br>
                                    Received By: (Name & Stamp)
                                    </p>
                                </div>
                            </div>
                            
                            <p class="text-center" style="font-size:12px!important;">
                                <strong>Make All Payable to <u>Fire Safety Trading (Pvt) Ltd</u></strong><br>
                                Thank You For Your Business ...!
                            </p>
                    </div>
                    
                    <div class="no-print" style="text-align:center!important">
                        <a href="" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-download"></i>Download With Header & Footer</a>
                        <a href="" class="btn btn-xs btn-success" target="_blank"><i class="fa fa-download"></i>Download With Out Header & Footer</a>
                    </div>
                </td>
            </tr>
        </tbody>
        @endforeach

        <tfoot>
            <tr>
                <td>
                    <div class="footer-space">&nbsp;</div>
                </td>
            </tr>
        </tfoot>
    </table>
    <div class="header"><img src="{{ asset('assets/header.jpg') }}"/></div>
     <!--style="width: 210mm!important;" -->
    <div class="footer"><img src="{{ asset('assets/footer.jpg') }}" style="width: 210mm!important;" /></div>
    

</div>
  
</body>
</html>