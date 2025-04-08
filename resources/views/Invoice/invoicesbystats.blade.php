@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Invoices</h1>
    </div>
</div>

<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
			    
			    <div class="col-12">
    				<div class="box-content">
    					<h4 class="box-title">Select Range</h4>
    					<div class="row">
    						<div class="col-xl-12">
    							<form action="{{ route('filter-invoice') }}" method="GET" class="form-horizontal">
    								<div class="form-group row">
    									<div class="col-sm-3">
    										<select name="company" class="form-control select2_1"><!--onchange="this.form.submit()"-->
                    			                <option value="">Select Company</option>
                    			                @foreach($companies as $company)
                    			                    <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                    			                @endforeach
                    			            </select>
    									</div>
    									<div class="col-sm-3">
    										<select name="status" class="form-control">
                    			                <option value="">Select Status</option>
                    			                <option value="Pending">Pending</option>
                    			                <option value="Cleared">Cleared</option>
                    			            </select>
    									</div>
    									<div class="col-sm-2">
    										<div class="input-group">
    											<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Search</button>
    										</div>
    									</div>
										<div class="col-sm-2">
										<a href="{{ route('invoicesByStatshow', ['data_key' => 'data_value']) }}" id="viewButton" class="btn btn-primary btn-sm">View</a>
										</div>
    								</div>
    							</form>
    						</div>
    					</div>
    				</div>
    			</div>
    			
    			
				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example2" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th style="min-width: 120px;">Date</th>
									<th>Invoice Number</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Company Name</th>
									 <th>Total Amount</th>
									<!--<th>Paid Amount</th>-->
									<th>Sales Tax Invoice</th>
									<th style="min-width: 120px;">Action</th>
									<th>Duplicate</th>
									<th>Convert To Sales</th>
									<th>Excel</th>
									<th>Payment Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									<th>Invoice Number</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Company Name</th>
									 <th>Total Amount</th> 
									<!-- <th>Paid Amount</th> -->
									<th>Sales Tax Invoice</th> 
									<th>Action</th>
									<th>Duplicate</th>
									<th>Convert To Sales</th>
									<th>Excel</th>
									<th>Payment Status</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Invoices)
									@foreach($Invoices as $Invoice)
										<tr>
											<td>{{ date("d M, Y", strtotime($Invoice->dated)) }}</td>
											<td>{{ $Invoice->invoice_no }}</td>
											<?php $customer = App\Models\Customer::find($Invoice->customer_id); ?>
											<td>{{ ($customer) ? $customer->customer_name : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->phone_no : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->company_name : 'N/A' }}</td>
											<?php 
											$InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
											$qty = 0;
											$unitPrice = 0;
											$totalPrice1 = 0;
											foreach($InvoiceProducts as $InvoiceProduct):
												$qty       += $InvoiceProduct->qty;
												$unitPrice = $InvoiceProduct->unit_price;
												$totalPrice1 += $InvoiceProduct->qty*$unitPrice;
											endforeach;

											$totalPrice2 = 0;
											if($Invoice->other_products_name):
	        									$moreProductsNames = explode('@&%$# ', $Invoice->other_products_name);
	        									$moreProductsQty   = explode('@&%$# ', $Invoice->other_products_qty);
	        									$moreProductsPrice = explode('@&%$# ', $Invoice->other_products_price);
	        									$moreProductsUnit = explode('@&%$# ', $Invoice->other_products_unit);
	        									$count2 = 0;
	        									foreach($moreProductsNames as $moreP):
		        									$qty 			=  $moreProductsQty[$count2]; 
		        									$price 			=  $moreProductsPrice[$count2];
		        									if(is_numeric($qty) && is_numeric($price)):
		        									$totalPrice2 	+= $qty*$price; 
		        									endif;
		        									$count2++; 
	        									endforeach;
        									endif;

        									$totalPrice = $totalPrice1+$totalPrice2; 
        									
        									// GST
        									if($Invoice->GST == 'on'):
        										$tax_rate = $Invoice->tax_rate/100;
	        									$tax = $totalPrice*$tax_rate;
	        								else:
	        									$tax = 0;
	        								endif;

											$Net_totalPrice = $totalPrice+$tax;
											
										    if(isset($Invoice->discount_percent)):
                		                        $discount_value = ($Net_totalPrice / 100) * $Invoice->discount_percent;
                		                    elseif(isset($Quote->discount_fixed)):
                                                $discount_value    = $Invoice->discount_fixed;
                                            else:
                                                $discount_value = 0;
                	                        endif;
                	                        

											// Transport
										    if(isset($Invoice->transportaion)):
										    	$transportaion = $Invoice->transportaion;
										    else:
										    	$transportaion = 0;
										    endif;
										    
                	                        $netTotal  = $Net_totalPrice+$transportaion-$discount_value;
											?>
											<td> 
												{{ number_format($netTotal, 2) }}
											</td>
											<td>
												@if($Invoice->sales_tax_invoice == 'on')
												<a target="_blank" href="{{ route('SalesTaxInvoice', $Invoice->id) }}" target="_blank"><i class="fa fa-download" aria-hidden="true"></i></a>
												@else
												N/A
												@endif
											</td>
											<td>
												<a href="{{ route('Invoice.show',$Invoice->invoice_no) }}" target="_blank" style="display: inline-block;color: #000">
													<i class="fa fa-download" aria-hidden="true"></i>
												</a>
												<a href="{{ route('Invoice.edit',$Invoice->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Invoice.destroy', $Invoice->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Invoice?');" style="display: inline-block;background: transparent; border:none;">
							                   			<i class="fa fa-trash-alt"></i>
							                	</button>
								            	</form>


								            	<!-- Trigger the modal with a button -->
												<a data-id="{{ $Invoice->id }}" class="passingID" data-toggle="modal" data-target="#myModal" style="display: inline-block;color: #000; background: transparent; border:none;">
													<i class="menu-icon fas fa-undo"></i>
												</a>
											</td>
											
											<td>
											   <a href="{{ route('duplicate-invoice',$Invoice->id) }}">Duplicate</a>
											</td>
											
											<td>
												<a href="{{ route('invoice-to-sales', $Invoice->id) }}" class="btn btn-xs btn-primary">Convert</a>
											</td>
											
											<td>
												<a href="{{ route('InvoiceExport', $Invoice->id) }}">Export</a>
											</td>

											<td>
												<form action="{{ route('invoice.status', $Invoice->id) }}" method="post">
												@csrf
													<select name="status" id="status" required class="form-control"
													onchange="this.form.submit();">
														<option value="Pending" 
														{{ ($Invoice->payment_status == "Pending") ? 'selected' : ''  }}>Pending</option>
														<option value="Cleared"
														{{ ($Invoice->payment_status == "Cleared") ? 'selected' : ''  }}>Cleared</option>
														
													</select>
												</form>
												<div style="display: flex; justify-content: center;">
													<a href="invoice-view-payment/{{ $Invoice->id }}" style="display: inline-block; color: #000;">
														<i class="fa fa-eye"></i>
													</a>
												</div>
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

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    
    <form action="{{ route('ReplicateInvoice') }}" method="POST">
    @csrf()
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Regenerate Invoice</h4>
          </div>
          <div class="modal-body">
                <input type="hidden" name="invoice_id" id="invoice_id">
                <label>Select Date</label>
                <input type="date" class="form-control" name="date" required>
          </div>
          <div class="modal-footer">
              <button type="submit" class="btn btn-success">Generate Invoice</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>
    </form>
  </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var viewButton = document.getElementById('viewButton');
        
        viewButton.addEventListener('click', function(e) {
            e.preventDefault(); // Prevent default link behavior

            // Get the current URL and its query parameters
            var currentUrl = window.location.href;
            var currentParams = new URLSearchParams(window.location.search);

            // Get the base URL for the 'invoicesByStatshow' route
            var baseUrl = "{{ route('invoicesByStatshow') }}";

            // Append the current query parameters to the base URL
            var updatedUrl = baseUrl + '?' + currentParams.toString();

            // Update the link's href attribute
            viewButton.href = updatedUrl;

            // Navigate to the updated URL
            window.location.href = updatedUrl;
        });
    });
</script>
@endsection
