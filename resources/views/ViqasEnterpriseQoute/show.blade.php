<!DOCTYPE html>
<html lang="en" >
    <head>
        <meta charset="UTF-8">
        <title>{{$quote->id}}-{{$quoteCustomer->customer_name}}-{{$quoteCustomer->city}}</title>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css'>
        <style>
            .header, .header-space
            {
              height: 162px!important;
            }

            .footer, .footer-space
            {
              height: 217px!important;
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
                height: 297mm;
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
                    height: 297mm;
                }
            }
            p {
                margin-top:0px!important;
                margin-bottom:0px!important;
            }
        </style>
    </head>
<body>

    <table>
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
			                    <div class="col-6">
			                        <p>
			                        	<strong>
			                        	REF: VE-Q-{{ $quote->id }}-{{ date('Y') }}
			                        	<br>
			                            To, 
			                            <br>
			                            {{ $quoteCustomer->customer_name }} 
			                            <br>
			                            {{ $quoteCustomer->city }}
			                            </strong>
			                        </p>
			                    </div>

			                    <div class="col-6">
			                        <strong>{{ date('d-m-Y') }}</strong>
			                    </div>
			                </div>

			                <div class="row">
			                    <div class="col-12">
			                        <p>
			                        	SUBJECT: <strong><u>{{ strtoupper($quote->subject) }}</u></strong>
			                        	<br>
			                        	Dear Sir, 
			                        	<br>
             							<span style="margin-left: 4em">Our best rates as following: </span>
			                        </p>
			                    </div>
			                </div>


			                <table border="0" cellspacing="0" cellpadding="0" style="margin-top: 1em!important">
			                    <thead>
			                        <tr>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;">S.No</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: left;!important;" class="text-left" colspan="3">Description</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;width:65px" class="text-center">SIZE</th>
			                            <!-- <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;" class="text-left">Pic</th> -->
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;" class="text-right">QTY</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;" class="text-center">UOM</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;" class="text-center">U.Price</th>
			                            <th style="background: #fff; border: 1px solid #000;font-weight:bolder;padding: 2px;text-align: center!important;" class="text-right">Amount</th>
			                        </tr>
			                    </thead>
			                    <tbody>    
				                    @if($QouteProducts)
									<?php $totalPrice = 0; $count = 1; ?>
									@foreach($QouteProducts as $Product)    
					                    <tr>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;">{{$count}}</td>
				                            <?php $productData = App\Models\Product::find($Product->product_id); ?>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-left" colspan="3">
				                                {!! $productData->description !!}
				                            </td>
				                            
				                          <!--   </td><td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;" class="text-left"><img src="/Product/{{ $productData->image }}" style="width:50px; height: 50px"></td> -->
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-center">
				                            	@if(isset($Product->productCapacity))
				                            	{{ $Product->productCapacity  }}
				                            	@else
				                            	1.00
				                            	@endif
				                            </td>
				                            
				                            
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="qty">{{ number_format($Product->qty, 2) }}</td>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{ $productData->unit }}</td>

				                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{ number_format($Product->unit_price,2)  }}</td>

				                            

				                            <?php 
				                            // Capacity
										    $price1 = $Product->unit_price;

										    $totalPrice += $Product->qty*$price1;
				                            ?>
				                            <td style="background: #fff; border: 1px solid #000;font-weight:400; text-align:right;padding: 2px;font-weight:bolder;" class="text-right">
				                            	{{ number_format($price1*$Product->qty,2)  }}
				                            </td>
				                        </tr>
							        	<?php $count++ ?>
									@endforeach
									@endif      

									@if($quote->other_products_name)
									<?php 
									$moreProductsNames = explode('@&%$# ', $quote->other_products_name);
									$moreProductsQty   = explode('@&%$# ', $quote->other_products_qty);
									$moreProductsPrice = explode('@&%$# ', $quote->other_products_price);
									$moreProductsUnit = explode('@&%$# ', $quote->other_products_unit);
									$count2 = 0;
									?>
									@foreach($moreProductsNames as $moreP)
									<tr>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;">{{$count}}</td>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" colspan="3">{{$moreP}}</td>
			                            
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;text-align:right;padding: 2px;font-weight:bolder;" class="text-center">
			                            1.00
			                        	</td>
			                        	
			                            <?php $qty =$moreProductsQty[$count2]; $price = $moreProductsPrice[$count2];?>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;"  class="qty">{{ number_format($moreProductsQty[$count2],2) }}</td>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{ ($moreProductsUnit[$count2]) ? $moreProductsUnit[$count2] : '' }}</td>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{ number_format($moreProductsPrice[$count2],2)  }}</td>

			                            

			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;text-align:right;padding: 2px;font-weight:bolder;" class="text-right">
			                            {{ number_format($qty*$price,2)  }}
			                        	</td>
			                        </tr>
			                        <?php $totalPrice += $qty*$price; $count++; $count2++; ?>
									@endforeach
									@endif                                 
			                    </tbody>
			                    <tfoot> 
			                        <tr>
			                            <td colspan="6" style="border:none"></td>
			                            <th colspan="2" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;">SUB-Total</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{number_format($totalPrice,2) }}</td>
			                        </tr>
			                        <?php 
			                        if($quote->GST == 'on'):
			                        	$GST = $totalPrice*0.17; 
			                    	else:
			                    		$GST = 0;
			                    	endif;
			                        ?>
			                        @if($quote->GST == 'on')
			                        <tr>
			                            <td colspan="6" style="border:none"></td>
			                            <th colspan="2" style="background: #fff; border: 1px solid #000;font-weight:bolder; color:blue;padding-left: 5.5em;;padding: 2px;">GST@17%</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{ number_format($GST,2) }}</td>
			                        </tr>
			                        @endif

			                        @if($quote->transportaion != '')
			                        <tr>
			                            <td colspan="6" style="border:none"></td>
			                            <th colspan="2" style="background: #fff; border: 1px solid #000;font-weight:bolder; color:blue;padding-left: 5.5em;;padding: 2px;">Transportaion</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{ number_format( $quote->transportaion,2) }}</td>
			                        </tr>
			                        @endif

			                        <tr>
			                            <td colspan="6" style="border:none"></td>
			                            <th colspan="2" style="background: #fff; border: 1px solid #000;font-weight:bolder;padding-left: 5.5em;;padding: 2px;">Grand Total</th>
			                            <td style="background: #fff; border: 1px solid #000;font-weight:400;padding: 2px;font-weight:bolder;" class="text-right">{{ number_format($totalPrice+$GST+$quote->transportaion,2)}}</td>
			                        </tr>
			                    </tfoot>
			                </table>
			                
			                <div>
			                	<strong><u>Terms & Conditions</u></strong>
			                	<br>
			                	<p>{!! $quote->termsConditions !!}</p>
			                </div>
			                
			                <div class="thanks">
			                	<strong>Best Regard,</strong>
			                </div>
			                
			                <div class="notices">
			                    <div>
			                    	<strong>VIQAS <br> Proprietor</strong>
			                    </div>
			                </div>
                    </div>

                    <div class="no-print" style="text-align:center!important">
			            <a href="{{ route('Viqas_down_with_header', $quote->id) }}" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-download"></i>Download with Header & Footer</a>
			            <a href="{{ route('Viqas_down_without_header', $quote->id) }}" class="btn btn-xs btn-success" target="_blank"><i class="fa fa-download"></i>Download with out Header & Footer</a>
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
    <div class="header"><img src="{{ asset('assets/viqas_header.png') }}" style="width: 210mm!important;" /></div>
    <div class="footer"><img src="{{ asset('assets/viqas_footer.png') }}" style="width: 210mm!important;" /></div>
    

</div>
  
</body>
</html>



