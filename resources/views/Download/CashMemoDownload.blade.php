<!DOCTYPE html>
<html lang="en" >
    <head>
        <?php $customer = App\Models\Customer::find($CashMemo->customer_id); ?>
        <meta charset="UTF-8">
        <title>
            {{$CashMemo->id}}
            @if(isset($customer))
            -{{$customer->company_name}} @if(isset($customer->city))-{{ $customer->city }} @endif
            @endif
            </title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
            .header, .header-space
            {
              height: 144px!important;
              /*height: 162px!important;*/
            }

            .footer, .footer-space
            {
                height: 180px!important;
              /*height: 200px!important;*/
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
                /* bottom:-30px; */
            }
            html, body {
                width: 210mm;
                height: 200mm; /*height: 297mm;*/
                font-size:12px!important;
              }
            @page {
              size: A4;
              margin: 0;
            }
            @media print {
                html, body {
                    width: 210mm;
                    height: 250mm; /*height: 297mm;*/
                }
            }
            p{
                margin-top:0px!important;
                margin-bottom:0px!important;
                padding-top:0px!important;
                padding-bottom:0px!important;
            }
            td {
                margin-top:0px!important;
                margin-bottom:0px!important;
                padding-top:0px!important;
                padding-bottom:0px!important;
            }
        </style>
    </head>
<body onload="print()">

    <table style="width: 100%;">
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
                    <div class="content" style="padding-left: 50px!important; padding-right: 50px!important;padding-top: 4rem;">
                       <div class="row">
                            <div class="col-12">
                                <h5 class="name" style="color:red;text-align:center; font-weight: bolder;font-size: 2em;font-size:15px!important;">CASH MEMO</h5>
                            </div>
                        </div>
                        <br>

                        <div class="row">
                            <?php $customer = App\Models\Customer::find($CashMemo->customer_id); ?>
                            <div class="col-12">
                                <p style="font-size:12px!important;">
                                    <strong>{{ ($customer) ? $customer->company_name : 'N/A' }}</strong>
                                    @if(isset($customer->address))
                                    <br>
                                    <strong>{{ $customer->address }}</strong>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-7">
                                <p style="font-size:12px!important;">
                                    Customer Order Date: 
                                    @if(isset($CashMemo->customer_order_date))
                                    {{ date("d M, Y", strtotime($CashMemo->customer_order_date)) }}
                                    @endif
                                    <br>
                                    Customer Order No: {{$CashMemo->customer_order_no }}
                                </p>
                            </div>
                            <div class="col-5">
                                <p style="font-size:12px!important;text-align: right;">
                                    Date: {{ date("d M, Y", strtotime($CashMemo->created_date)) }} <br>
                                    Reference No: {{ $CashMemo->id }} <br>
                                </p>
                            </div>
                        </div>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="padding:2px; text-align: center;width:3%!important;font-size:12px;">S.No</th>
                                    <th style="padding:2px; text-align: center;width:60%!important;font-size:12px;">Descriptions</th>
                                    <th style="padding:2px; text-align: center;width:8%!important;font-size:12px;">Size</th>
                                    <th style="padding:2px; text-align: center;width:8%!important;font-size:12px;">Qty</th>
                                    <th style="padding:2px; text-align: center;width:15%!important;font-size:12px;">Unit Price</th>
                                    <th style="padding:2px; text-align: center;width:15%!important;font-size:12px;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($CashmemoProducts)
                                    <?php $totalPrice = 0; $count = 1; ?>
                                    @foreach($CashmemoProducts as $Product)    
                                        <tr>
                                         
                                            
                                            <?php $productData = App\Models\Product::find($Product->product_id); ?>
                                            <td style="background: #fff; border: 1px solid #000;padding: 2px; width: 5%!important;font-size:12px!important;" class="text-center">{{$count}}</td>
                                    <?php $productData = App\Models\Product::find($Product->product_id); ?>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-left">
				                                {!! (isset($Product->description)) ? $Product->description : $productData->description !!}
				                    </td>
  
                                            
                                            <td style="padding:2px; text-align: center;font-size:12px!important;" class="text-center">
                                                {{ $Product->productCapacity }}
                                            </td>
                                            
                                            <td style="padding:2px; text-align: center;font-size:12px!important;" class="text-center" class="qty">{{ number_format($Product->qty, 2) }}</td>

                                            <td style="padding:2px; text-align: center;font-size:12px!important;" class="text-center">{{ number_format($Product->unit_price,2)  }}</td>

                                            <?php 
                                            $subtotal   = $Product->qty*$Product->unit_price;
                                            $totalPrice += $subtotal;
                                            ?>
                                            <td style="padding:2px; text-align: center;font-size:12px!important;" class="text-right">
                                                {{ number_format($subtotal,2)  }}
                                            </td>
                                        </tr>
                                        <?php $count++ ?>
                                    @endforeach
                                @else
                                <?php $totalPrice=0; ?>
                                @endif     

                                <?php 
                                $Descriptions   = explode('@&%$# ', $CashMemo->descriptions);
                                $Quantity       = explode('@&%$# ', $CashMemo->qty);
                                $UnitPrice      = explode('@&%$# ', $CashMemo->unit_price);
                                $productCapacity      = explode('@&%$# ', $CashMemo->productCapacity);
                                $total = $totalPrice;
                                ?>
                                @if(!empty($Descriptions))
                                    <?php $count1=0; ?>
                                    @foreach($Descriptions as $Description)
                                        @if(!empty($Description))
                                        <tr>
                                            <td style="padding:2px; text-align: center;font-size:12px!important;">{{ $count }}</td>
                                            <td style="padding:2px; text-align: left;font-size:12px!important;">{{ $Description }}</td>
                                            <td style="padding:2px; text-align: center;font-size:12px!important;">{{ $productCapacity[$count1] }}</td>
                                            <td style="padding:2px; text-align: center;font-size:12px!important;">{{ number_format( (float)$Quantity[$count1], 2) }}</td>
                                            <td style="padding:2px; text-align: right;font-size:12px!important;">{{ number_format( (float)$UnitPrice[$count1], 2) }}</td>

                                            <?php 
                                            $qty    = (float)$Quantity[$count1];
                                            $price  = (float)$UnitPrice[$count1];
                                            
                                            $sub = $price;

                                            $net = $qty*$sub;
                                            $total += $net;
                                            ?>
                                            <td style="padding:2px; text-align: right;font-size:12px!important;">{{ number_format( $net, 2) }}</td>
                                        </tr>
                                        <?php $count++;$count1++; ?>
                                        @endif
                                    @endforeach
                                @endif
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="6" class="text-right" style="padding:2px; text-align: right;font-size:12px!important;">
                                        Total: {{ number_format( $total, 2) }}
                                    </th>
                                </tr>

                                <?php $new_total_price=0; $transportaion = 0;?>
                                @if($CashMemo->discount_percent > 0 || $CashMemo->discount_fixed > 0)
                                <?php
                                if(isset($CashMemo->discount_percent)):
                                    $discount_value     = ($total / 100) * $CashMemo->discount_percent;
                                    $new_total_price    = $total - $discount_value;
                                elseif(isset($CashMemo->discount_fixed)):
                                    $discount_value = $CashMemo->discount_fixed;
                                    $new_total_price    = $total - $discount_value;
                                endif;
                                if (isset($CashMemo->transportaion) && $CashMemo->transportaion > 0):
                                    $transportaion = $CashMemo->transportaion;
                                endif;
                                ?>

                                <tr>
                                    <th colspan="5" class="text-right" style="padding:2px; text-align: right;font-size:12px!important;">
                                        Discount:
                                    </th>
                                    <th colspan="1" class="text-right" style="padding:2px; text-align: right;font-size:12px!important;">
                                        {{ number_format( $new_total_price, 2) }}
                                    </th>
                                </tr>
                                @endif

                                @if($CashMemo->transportaion > 0)
                                <tr>
                                    <th colspan="5" class="text-right" style="padding:2px; text-align: right;font-size:12px!important;">
                                        Transportaion:
                                    </th>
                                    <th colspan="1" class="text-right" style="padding:2px; text-align: right;font-size:12px!important;">
                                        {{ number_format( $CashMemo->transportaion,2) }}
                                    </th>
                                </tr>
                                @endif

                                <?php $grand_total = $new_total_price+$transportaion; ?>

                                @if($CashMemo->discount_percent > 0 || $CashMemo->discount_fixed > 0 || $CashMemo->transportaion > 0)
                                <tr>
                                    <th colspan="5" class="text-right" style="padding:2px; text-align: right;font-size:12px!important;">
                                        Grand Total:
                                    </th>
                                    <th colspan="1" class="text-right" style="padding:2px; text-align: right;font-size:12px!important;">
                                        {{ number_format( $grand_total,2) }}
                                    </th>
                                </tr>
                                @endif
                            </tfoot>
                        </table>

                        <div class="row">
                            <div class="col-5" style="font-size:12px;">
                                @if($CashMemo->user_id != '')
                                    <?php $user = App\Models\User::find($CashMemo->user_id);?>
                                    @if(isset($user))
                                        @if($user->signature != '')
                                        <img src="/signature/{{ $user->signature }}" style="width:100px; height:60px">
                                        @endif
                                        <p>Direct All Inquiries To: <br>
                                        {{ $user->username }}  <br>
                                        |Tel: {{ Auth::user()->Tel }} | Cell: {{ Auth::user()->phone_number }}|  <br>
                                        |E-Mail: {{" sales@firesafetytrading.com.pk "}}|
                                    @endif
                                @endif
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6">
                                <br><br><br>
                                <p style="font-size:12px;"> <br> 
                                _________________________________________<br>
                                Received By: (Name & Stamp)
                                </p>
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
    <div class="header"><img src="{{ asset('assets/header2.jpeg') }}" style="width: 810px; height:100%;" /></div>
    <!--style="width: 210mm!important;" -->
<div class="footer"><img src="{{ asset('assets/footer2.jpeg') }}" style="width: 120%; height:100%;" /></div>

</div>
  
</body>
</html>