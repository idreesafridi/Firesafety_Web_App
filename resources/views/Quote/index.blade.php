@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Quotes</h1>
    </div>
</div>

<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
			    <div class="col-3">
			        <form action="{{ route('filter-quote') }}" method="get">
			            
			        </form>
			    </div>
			    
			    <div class="col-12">
    				<div class="box-content">
    					<h4 class="box-title">Select Range</h4>
    					<div class="row">
    						<div class="col-xl-12">
    							<form action="{{ route('filter-quote') }}" method="GET" class="form-horizontal">
    								<div class="form-group row">
    									<div class="col-sm-3">
    										<select name="company" class="form-control select2_1" onchange="this.form.submit()">
                    			                <option value="">Select Company</option>
                    			                @foreach($companies as $company)
                    			                    <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                    			                @endforeach
                    			            </select>
    									</div>
    									<div class="col-sm-2">
    										<div class="input-group">
    											<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Search</button>
    										</div>
    									</div>
    								</div>
    							</form>
    						</div>
    					</div>
    				</div>
    			</div>
			
			    
				<div class="col-12">
				    
					<div class="box-content table-responsive">
						<table id="exampleQuote" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th style="min-width: 100px;">Date</th>
									<th style="min-width: 100px;">Quote No</th>
									<th style="min-width: 150px;">Customer Name</th>
									<th style="min-width: 150px;">Customer Phone</th>
									<th>Company Name</th>
									<th>Subject</th>
									<th>Amount</th>
									<th style="min-width: 80px;">Action</th>
									<th style="min-width: 50px;">Duplicate</th>
									<th style="min-width: 100px;">To Invoice</th>
									<th style="min-width: 50px;">Excel</th>
									<th style="min-width: 100px;">Convert To Support Quote</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									<th>Quote No</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Company Name</th>
									<th>Subject</th>
									<th>Amount</th>
									<th>Action</th>
									<th style="min-width: 50px;">Duplicate</th>
									<th>To Invoice</th>
									<th>Excel</th>
									<th>Convert To Support Quote</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Quotes)
									@foreach($Quotes as $Quote)
										<tr>
											<td>{{ date("d M, Y", strtotime($Quote->dated)) }}</td>
											<td>{{ $Quote->id }}</td>
											<?php $customer = App\Models\Customer::find($Quote->customer_id); ?>
											<td>{{ ($customer) ? $customer->customer_name : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->phone_no : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->company_name : 'N/A' }}</td>
											
											<td>{{ $Quote->subject }}</td>
											
											<?php 
											$totalPrice1 = 0; 
											$QouteProducts = App\Models\QouteProducts::where('quote_id', $Quote->id)->get();
											foreach($QouteProducts as $Product):
											    $productData = App\Models\Product::find($Product->product_id);
											    $price1 = $Product->unit_price;
											    $totalPrice1 += $Product->qty*$price1; // products prices
											endforeach;
        									$totalPrice2 = 0;
											?>
											@if($Quote->other_products_name)
	        									<?php 
	        									$moreProductsNames = explode('@&%$# ', $Quote->other_products_name);
	        									$moreProductsQty   = explode('@&%$# ', $Quote->other_products_qty);
	        									$moreProductsPrice = explode('@&%$# ', $Quote->other_products_price);
	        									$moreProductsUnit = explode('@&%$# ', $Quote->other_products_unit);
	        									$count2 = 0;
	        									?>
	        									@foreach($moreProductsNames as $moreP)
		        									<?php 
		        									$qty 			=  (float)$moreProductsQty[$count2]; 
		        									$price 			=  (float)$moreProductsPrice[$count2];
		        									$totalPrice2 	+= $qty*$price; 
		        									$count2++; 
		        									?>
	        									@endforeach
        									@endif   
        									
        									<?php 
        									$totalPrice = $totalPrice1+$totalPrice2; // added other products
        									if(isset($Quote->discount_percent)):
            			                        $discount_value     = ($totalPrice / 100) * $Quote->discount_percent;
                                                $totalPrice    = $totalPrice - $discount_value;
                                            elseif(isset($Quote->discount_fixed)):
                                                $totalPrice    = $totalPrice - $Quote->discount_fixed;
        			                        endif;

        			                        // Transport
										    if(isset($Quote->transportaion)):
										    	$transportaion = $Quote->transportaion;
										    else:
										    	$transportaion = 0;
										    endif;
										    
											if(isset($Quote->gst_fixed)):
										    	$gst_fixed = $Quote->gst_fixed;
										    else:
										    	$gst_fixed = 0;
										    endif;
										    
        									// GST
    			                            $tax_rate = $Quote->tax_rate/100;
        									if($Quote->GST == 'on'):
	        									$GST = $totalPrice*$tax_rate;
	        								else:
	        									$GST = 0;
	        								endif;
        									$net = $totalPrice+$GST+$gst_fixed; //+$transportaion;
        									
        									if($Quote->WHT == 'on'):
            			                        $wh_tax = $Quote->wh_tax/100;
            			                        if($Quote->WHT == 'on'):
            			                        	$wh_tax_amount = $totalPrice*$wh_tax;  
            			                    	else:
            			                    		$wh_tax_amount = 0;
            			                    	endif;
            			                    	$net = $net+$wh_tax_amount;
        			                        endif;
        			                        
        			                        $net += $transportaion;
        									?>


											<td>{{ number_format($net,2) }}</td>
											<td>
												@if($Quote->attachment != '')
												<a href="Quote/{{ $Quote->attachment }}" target="_blank" download style="display: inline-block;color: #000">
													<i class="fa fa-download"></i>
												</a>
												@else
												<!-- route('choose-template',$Quote->id)-->
												<a href="{{ route('TemplateOne', $Quote->id) }}" target="_blank" style="display: inline-block;color: #000">
													<i class="menu-icon fa fa-download"></i>
												</a>
												<a href="{{ route('Quotes.edit',$Quote->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Quotes.destroy', $Quote->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Quote?');" style="display: inline-block;background: transparent; border:none;">
							                   			<i class="fa fa-trash-alt"></i>
							                	</button>
								            	</form>
								            	@endif
											</td>
											
											
											<td>
											   <a href="{{ route('duplicate-quote',$Quote->id) }}">Duplicate</a>
											</td>
											<td>
												@if($Quote->attachment == '')
												<a href="{{ route('ConvertQuotation',$Quote->id) }}">Convert</a>
												@else
												N/A
												@endif
											</td>
											<td>
												<a href="{{ route('QouteExport', $Quote->id) }}">Export</a>
											</td>
											<td>
												<a href="{{ route('ConvertToSupportQuote', $Quote->id) }}">
													Convert
												</a>
											</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>		
			<footer class="footer">
				<ul class="list-inline">
					<li>2020 © Al Akhzir Tech.</li>
				</ul>
			</footer>
		</div>
		<!-- /.main-content -->
</div>

@endsection
