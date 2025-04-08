<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <?php $customer = App\Models\Customer::find($Challan->customer_id); ?>
        <title>
            {{$Challan->id}}
            @if(isset($customer))
            -{{ ($customer) ?  $customer->company_name : '' }} @if(isset($customer))-{{ $customer->city }} @endif
            @endif
            </title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
            .header, .header-space
            {
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
            .remarks p{
              margin: 0px!important;
            }
            p{
                margin-top:0px;
                margin-bottom:0px;
            }
        </style>
    </head>
<body onload="print()">

    <table style="width:100%!important">
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
                            <div class="col-12 text-center">
                                <p style="color: red; font-weight: bold;font-size: 2em;font-size:15px!important;">DELIVERY CHALLAN</p>
                            </div>
                        </div>

                        <table class="table">
                            <tbody>
                                <tr>
                                    <th style="padding:2px; border: 1px solid black; text-align: left;width:20%!important;font-size:12px!important;border:none">M/S</th>
                                    <?php $customer = App\Models\Customer::find($Challan->customer_id); ?>
                                    <td colspan="3" style="padding:2px; border: 1px solid black; text-align: left;width:80%!important;font-size:12px!important;border:none; border-bottom:1px solid #969696;vertical-align: bottom;">
                                        {{ ($customer) ? $customer->company_name : 'N/A' }}
                                    </td>
                                </tr>
                                
                                @if(isset($customer->address))
                                <tr>
                                    <th style="border:none"></th>
                                    <td colspan="3" style="padding:2px; border: 1px solid black; text-align: left;width:80%!important;font-size:12px!important;border:none; border-bottom:1px solid #969696;vertical-align: bottom;">
                                        {{ $customer->address }}
                                    </td>
                                </tr>
                                @endif
                                
                                <tr>
                                    <th style="padding:2px; border: 1px solid black; text-align: left;width:15%!important;font-size:12px!important;border:none">Customer Order No.</th>
                                    <td style="padding:2px; border: 1px solid black; text-align: left;width:35%!important;font-size:12px!important;border:none; border-bottom:1px solid #969696;vertical-align: bottom;">
                                        {{ $Challan->customer_order_no }}
                                    </td>
                                    
                                    <th style="padding:2px; border: 1px solid black; text-align: left;width:20%!important;font-size:12px!important;border:none">&nbsp;&nbsp;Customer Ref No.</th>
                                    <td style="padding:2px; border: 1px solid black; text-align: left;width:30%!important;font-size:12px!important;border:none; border-bottom:1px solid #969696;vertical-align: bottom;">
                                        {{ $Challan->id }}
                                    </td>
                                </tr>
                                
                                
                                <tr>
                                    <th style="padding:2px; border: 1px solid black; text-align: left;width:15%!important;font-size:12px!important;border:none">Customer Order Date</th>
                                    <td style="padding:2px; border: 1px solid black; text-align: left;width:35%!important;font-size:12px!important;border:none; border-bottom:1px solid #969696;vertical-align: bottom;">
                                        @if($Challan->customer_order_date)
                                        {{ date("d M, Y", strtotime($Challan->customer_order_date)) }}
                                        @else
                                        N/A
                                        @endif
                                    </td>
                                    
                                    <th style="padding:2px; border: 1px solid black; text-align: left;width:20%!important;font-size:12px!important;border:none">&nbsp;&nbsp;Our Quote No</th>
                                    <td style="padding:2px; border: 1px solid black; text-align: left;width:30%!important;font-size:12px!important;border:none; border-bottom:1px solid #969696;vertical-align: bottom;">
                                        @if($Challan->reference_no)
                                            {{ $Challan->reference_no }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th style="padding:2px; border: 1px solid black; text-align: left;width:20%!important;font-size:12px!important;border:none">&nbsp;&nbsp;Dated</th>
                                    <td colspan="3" style="padding:2px; border: 1px solid black; text-align: left;width:30%!important;font-size:12px!important;border:none; border-bottom:1px solid #969696;vertical-align: bottom;">
                                        @if($Challan->created_date)
                                            {{ date("d M, Y", strtotime($Challan->created_date)) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                  
                        <br>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="padding:2px; border: 1px solid black; text-align: center;width:3%!important;font-size:12px!important;">S.No</th>
                                    <th style="padding:2px; border: 1px solid black; text-align: center;width:70%!important;font-size:12px!important;">Descriptions</th>
                                    <th style="padding:2px; border: 1px solid black; text-align: center;width:14%!important;font-size:12px!important;">Size</th>
                                    <th style="padding:2px; border: 1px solid black; text-align: center;width:2%!important;font-size:12px!important;">Quantity</th>
                                    <th style="padding:2px; border: 1px solid black; text-align: center;width:1%!important;font-size:12px!important;">UOM</th>
                                    <th style="padding:2px; border: 1px solid black; text-align: center;width:30%!important;font-size:12px!important;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $Descriptions   = explode('@&%$# ', $Challan->descriptions);
                                $Quantity     =  explode('@&%$# ', $Challan->qty);
                                $Remarks    =  explode('@&%$# ', $Challan->remarks);
                                $Unit     =  explode('@&%$# ', $Challan->unit);
                                $productCapacity      = explode('@&%$# ', $Challan->productCapacity);
                                ?>
                                @if(!empty($Descriptions))
                                <?php $count=0; $count1=1; ?>
                                @foreach($Descriptions as $Description)
                                @if(!empty($Description))
                                    <tr>
                                        <td style="padding:5px; border: 1px solid black; text-align: center;font-size:12px!important;">{{ $count1 }}</td>
                                        <td style="padding:5px; border: 1px solid black; text-align: left;font-size:12px!important;">{{ $Description }}</td>
                                        <td style="padding:5px; border: 1px solid black; text-align: center;font-size:12px!important;">{{ (isset($productCapacity[$count])) ? $productCapacity[$count] : '' }}</td>
                                        <td style="padding:5px; border: 1px solid black; text-align: right;font-size:12px!important;">{{ (isset($Quantity[$count])) ? number_format((float)$Quantity[$count], 2) : '' }}</td>
                                        <td style="padding:5px; border: 1px solid black; text-align: center;font-size:12px!important;">{!! (isset($Unit[$count])) ? $Unit[$count] : '' !!}</td>
                                        <td style="padding:5px; border: 1px solid black; text-align: left;font-size:12px!important;" class="remarks">{!! (isset($Remarks[$count])) ? $Remarks[$count] : '' !!}</td>
                                    </tr>
                                    <?php $count++;$count1++; ?>
                                @endif
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                       
                         <div class="row">
                            <div class="col-12" style="font-size:12px!important;">
                              <span><br><br>________________________________<br>Stores Incharge</span>
                              <span style="float: right;margin-top: -24px">________________________________<br>Receiver's Signature</span>
                            </div>
                        </div>
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
</div>
  
</body>
</html>