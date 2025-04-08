@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Update Invoice</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('Invoice.update', $Invoice->id) }}" method="POST">
@csrf()
@method('PATCH')
<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<div class="col-12">
				@if ($errors->any())
	                <div class="alert alert-danger">
	                    <ul>
	                        @foreach ($errors->all() as $error)
	                            <li>{{ $error }}</li>
	                        @endforeach
	                    </ul>
	                </div>
	            @endif
				<div class="box-content">
					<h4 class="box-title">Update Invoice</h4>
					<hr>
					
					<h4 class="box-title">Customer</h4>
					
					<div class="form-group">
					    <div class="row">
					        <?php 
						    $customer = App\Models\Customer::find($Invoice->customer_id);
						    if(isset($customer)):
						    if($customer->type == 'regular'):
						        $c_company  = $customer->company_name;
						        $c_city     = $customer->city;
						        $c_address  = $customer->address;
						        $c_id       = $customer->id;
						        $c_name       = $customer->customer_name;
						    else:
						        $c_company  = '';
						        $c_city     = '';
						        $c_address  = '';
						        $c_id       = '';
						        $c_name       = '';
						    endif;
						    else:
						        $c_company  = '';
						        $c_city     = '';
						        $c_address  = '';
						        $c_id       = '';
						        $c_name       = '';
						    endif;
						    ?>
						    <!--Company Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_company_name" id="customer_company_name">
									<option value="">Select Customer</option>
									@if($customers)
									@foreach($customers as $cust)
									<option value="{{ $cust->company_name }}" <?php echo ($cust->company_name == $c_company) ? 'selected' : '';?>>
									    {{ $cust->company_name }}
									</option>
									@endforeach
									@endif
								</select>
								<div class="help-block with-errors"></div>
							</div>
							<!--City-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_city" id="customer_city">
								    @if(isset($c_city))
								    <option value="{{ $c_city }}">{{ $c_city }}</option>
								    @endif
								</select>
							</div>
							<!--Address-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_address" id="customer_address">
								    @if(isset($c_address))
								    <option value="{{ $c_address }}">{{ $c_address }}</option>
								    @endif
								</select>
							</div>
							<!--Customer Name-->
							<div class="form-group col-md-3">
								<select class="form-control select2_1" name="customer_id" id="customer_id">
								    @if(isset($c_id))
								    <option value="{{ $c_id }}">{{ $c_name }}</option>
								    @endif
								</select>
								<div class="help-block with-errors"></div>
							</div>
							
					    </div>
						<div class="row">							
        					<div class="col-md-3">
        					    <input type="text" class="form-control" id="customer_ntn_no" name="customer_ntn_no" placeholder="Customer NTN No." value="{{ $Invoice->customer_ntn_no }}" style="height: 30px!important;">
        					</div>
							<div class="col-md-3">
        					    <input type="text" class="form-control" id="customer_po_no" name="customer_po_no" placeholder="Customer PO #" value="{{ $Invoice->customer_po_no }}" style="height: 30px!important;">
        					</div>
							<div class="form-group col-md-3">
								{{-- new code --}}
								<select class="form-control select2_1" name="branch" id="" required>
									<option value="" {{ !$selectedBranchName ? 'selected' : '' }}>Select Branch</option>
									@if($Branches)
										@foreach($Branches as $Branch)
											<option value="{{ $Branch->branch_name }}" {{ (old('branch', $selectedBranchName) == $Branch->branch_name) ? 'selected' : '' }}>
												{{ $Branch->branch_name }}
											</option>
										@endforeach
									@endif
								</select>
								<div class="help-block with-errors"></div>
							</div>       
							<div class="col-md-3">
        					    <input type="number" class="form-control" id="invoice_no" name="invoice_no" placeholder="Invoice No" value="{{ $Invoice->invoice_no }}" style="height: 30px!important;">
        					</div>	
        				</div>
        			</div>
					<hr>
					<h4 class="box-title">Product Details</h4>
					<div class="form-group">
						<div class="row">
						

							<table class="table" id="customerTable">
								<?php $count=0; $sequence=1; ?>
                            @if($InvoiceProducts->count() > 0)
							    @foreach($InvoiceProducts as $IProduct)
								<tr>
									<th colspan="6"><input type="text" name="heading[]" class="form-control" value="{{ $IProduct->heading }}"></th>
								</tr>
								    <tr>
								        <td style="width:5%;border: none;">
    										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
    									</td>
								        
    									<td style="width:25%;border: none;" class="productTD">
    										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;width:100%">
    											<option value="">Select Product</option>
    											@if($products)
    											@foreach($products as $product)
    											<option value="{{ $product->id }}" <?php echo ($product->id == $IProduct->product_id) ? 'selected' : ''; ?>>{{ $product->name }}</option>
    											@endforeach
    											@endif
    										</select>
    										<div class="help-block with-errors"></div>
    									</td>
    									<td style="width:20%;border: none;">
    										<input type="number" class="form-control qty" id="qty" name="qty[]" placeholder="Quantity" value="{{ $IProduct->qty }}" style="height: 30px!important;">
    										<input type="hidden" name="available_inventory" value="{{ $IProduct->product->inventory }}" class="available_inventory">
    									</td>
    									<?php
    									$productData = App\Models\Product::find($IProduct->product_id);
    									$capacities  = explode(', ', $productData->capacity);
    									?>
    									<td style="width:20%;border: none;" class="productCapacityTD">
    										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
    										@foreach($capacities as $capacity) 
    										    <option value="{{ $capacity }}" <?php echo ($capacity == $IProduct->productCapacity) ? 'selected' : ''; ?>>{{ $capacity }}</option>
    										@endforeach
    										</select>
    										<div class="help-block with-errors"></div>
    									</td>
                                        <td style="width:20%;border: none;">
    										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" value="{{ $IProduct->unit_price }}" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									
    									<td style="width:20%;border: none;">
    										<div class="input-group">
    										    <a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
        										&nbsp;
        										<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
    										</div>
    									</td>
										<tr  data-trid="{{ $sequence }}">
                                            <th colspan="6">
											<div style="display: flex; align-items: center;">
                                                <a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>
												<div class="form-group col-md-2" style="text-align: left; margin-left: 10px;margin-top: 10px;">
											      <input type="number" step="0.01" class="form-control" id="gst_product" name="gst_product[]" value="{{ $IProduct->gst_product}}" placeholder="GST Product"  style="height: 30px!important;">
										        </div>
												</div>
												<div class="txtdescription_wrapper">
												<textarea id="tmce_{{ $sequence }}" name="description[]" class="form-control description3 txtdescription " placeholder="Description"  style="height: 200px">
												{{ isset($IProduct->description) ? $IProduct->description : $productData->description }}
                                                </textarea>
												</div>
                                                
                                            </th>
                                        </tr>							<?php $count++; $sequence++; ?>
    								</tr>
    								<?php $count++; $sequence++; ?>
								@endforeach
							@else
							<tr>
								<th colspan="6"><input type="text" name="heading[]" class="form-control"></th>
							</tr>
								<tr>
							        <td style="width:5%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
									</td>
									<td style="width:25%" class="productTD">
										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;width:100%">
											<option value="">Select Product</option>
											@if($products)
											@foreach($products as $product)
											<option value="{{ $product->id }}">{{ $product->name }}</option>
											@endforeach
											@endif
										</select>
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%">
										<input type="number" class="form-control qty" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;">
										<input type="hidden" name="available_inventory" class="available_inventory">
									</td>
									<td style="width:20%" class="productCapacityTD">
										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
										</select>
										<div class="help-block with-errors"></div>
									</td>
                                    <td style="width:20%">
										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									
									<td style="width:20%">
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
											&nbsp;
        									<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
										</div>
									</td>
									<tr>
										<th colspan="6">
										<div style="display: flex; align-items: center;">
											<a class="btn btn-xs btn-primary view_hide" style="color: #fff;">View/Hide Description</a>
											<div class="form-group col-md-2" style="text-align: left; margin-left: 10px;margin-top: 10px;">
											  <input type="number" step="0.01" class="form-control" id="gst_product" name="gst_product[]" value="" placeholder="GST Product"  style="height: 30px!important;">
										    </div>
											<textarea id="tmce_1"  name="description[]" class="form-control description" placeholder="Description" style="height: 50px;display: none;"></textarea>
										</th>
									</tr>
								</tr>
							@endif
							</table>
							
							<hr>
							<div class="col-md-12">
							 	<h4 class="box-title">Other Products</h4>
							</div>
                            
                            <?php
							$ops_name   = explode('@&%$# ', $Invoice->other_products_name);
                            $op_qty     = explode('@&%$# ', $Invoice->other_products_qty);
                            $op_price   = explode('@&%$# ', $Invoice->other_products_price);
                            $op_unit    = explode('@&%$# ', $Invoice->other_products_unit);
                            $op_size    = explode('@&%$# ', $Invoice->other_products_size);
                            // $op_image   = explode('@&%$# ', $Invoice->other_products_image);
                            
                            $count1=0;
                            $sequence_2=1;
                            ?>
                            
							<table class="table" id="productsTable">
							    @if(isset($ops_name[0]))
    							    @foreach($ops_name as $op_name)
    							    @if(!empty($op_name))
								    <tr>
								    	<td style="width:5%;">
    										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence_2 }}" style="height: 30px!important;">
    									</td>

    									<td style="width:25%;">
    										<input type="text" class="form-control" id="productName" name="productName[]" value="{{ $op_name }}" placeholder="Product Name" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									<td style="width:20%;">
    										<input type="number" class="form-control" id="productqty" name="productQty[]" value="{{ $op_qty[$count1] }}" placeholder="Quantity" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									<td style="width:20%;">
    										<input type="text" class="form-control" id="size" name="size[]" value="{{ (isset($op_size[$count1])) ? $op_size[$count1] : '' }}" placeholder="Size" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									<td style="width:20%;">
    										<input type="number" step="0.01" class="form-control" id="productPric" value="{{ $op_price[$count1] }}" name="productPric[]" placeholder="Price" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									<td style="width:20%;">
    										<input type="text" class="form-control" id="unit" name="unit[]" value="{{ $op_unit[$count1] }}" placeholder="Unit" style="height: 30px!important;">
    										<div class="help-block with-errors"></div>
    									</td>
    									
    									<td style="width:20%;">
    										<div class="input-group">
    											<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct" style="color: #fff"><i class="fa fa-plus"></i></a>
												&nbsp;
												<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
    										</div>
    									</td>
    								</tr>
								    <?php $count1++; $sequence_2++; ?>
								    @endif
    								@endforeach
    							@endif 

    							@if($count1 == 0)
    							<tr>
    								<td style="width:5%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence_2 }}" style="height: 30px!important;">
									</td>
									<td style="width:25%;">
										<input type="text" class="form-control" id="productName" name="productName[]" placeholder="Product Name" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;">
										<input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;">
										<input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;">
										<input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;">
										<input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>

									<td style="width:20%;"> 
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct" style="color: #fff"><i class="fa fa-plus"></i></a>
											&nbsp;
											<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
										</div>
									</td>
								</tr>
								@endif
							</table>
							
							

                        <div class="row" style="padding-left: 1rem;padding-right: 1rem;margin-top:1rem; margin-bottom:1rem;">
							@if(Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin')
							<div class="form-group col-md-2">
								<input type="checkbox" id="refill_notification" name="refill_notification"  <?php echo ($Invoice->refill_notification == 'on') ? 'checked' : '' ?>> Refill Notification
								<div class="help-block with-errors"></div>
							</div>
							@endif
							
							<div class="form-group col-md-1" style="text-align: right;padding: 0;">
								<input type="checkbox" id="GST" name="GST" <?php echo ($Invoice->GST == 'on') ? 'checked' : '' ?>>
							</div>
							
							<div class="form-group col-md-2" style="text-align: left;padding: 10;px">
								<input type="text" id="gst_text" name="gst_text" class="form-control" value="{{ $Invoice->gst_text }}" required>
							</div>

							<div class="form-group col-md-2">
								<input type="number" step="0.01" class="form-control" id="tax_rate" name="tax_rate" value="{{ $Invoice->tax_rate }}" placeholder="Tax Rate"  style="height: 30px!important;">
							</div>
							<div  class="form-group col-md-2" style="text-align: left;padding: 10;px">
    								<input type="number" class="form-control" id="gst_fixed" name="gst_fixed" value="{{ $Invoice->gst_fixed}}" placeholder="GST Fixed"  style="height: 30px!important;">
    							</div>
							<div class="form-group col-md-2" style="text-align: right;padding: 0;">
								<input type="checkbox" id="WHT" name="WHT" value="on" <?php echo ($Invoice->WHT == 'on') ? 'checked' : '' ?>> WH.Tax
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-1" style="text-align: left;padding: 10;px">
								<input type="number" step="0.01" class="form-control" id="wh_tax" name="wh_tax" value="{{ $Invoice->wh_tax }}" placeholder="W.H Tax"  style="height: 30px!important;">
							</div>
							
							<div class="form-group col-md-2">
								<input type="number" class="form-control" id="discount_percent" name="discount_percent" value="{{ $Invoice->discount_percent }}" placeholder="Discount (%)"  style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
						</div>

					    <div class="row" style="padding-left: 1rem;padding-right: 1rem;margin-top:1rem; margin-bottom:2rem;">
					        
					        <div class="form-group col-md-2">
							    <label>Discount Fixed</label>
								<input type="number" class="form-control" id="discount_fixed" name="discount_fixed" value="{{ $Invoice->discount_fixed }}" placeholder="Discount Fixed"  style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-2">
							    <label>Transportaion</label>
								<input type="number" step="0.01" class="form-control" id="transportaion" name="transportaion" value="{{ $Invoice->transportaion }}" placeholder="Transportaion" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
							
							<div class="form-group col-md-2">
							    <label>Date</label>
								<input type="date" class="form-control" id="dated" name="dated" value="{{ $Invoice->dated }}" style="height: 30px!important;">
							</div>
							
							<div class="form-group col-md-2">
							    <label>Our Quote No.</label>
								<input type="text" class="form-control" id="quote_id" name="quote_id" value="{{ $Invoice->quote_id }}" placeholder="Our Quote No" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
							
							<?php
							if(isset($Invoice->delievery_challan_no)):
							    $delievery_challan_no = $Invoice->delievery_challan_no;
							else:
                                $quote_id           = $Invoice->quote_id;
                                if(isset($quote_id)):
    	                            $delievery_challan  = App\Models\Challan::where('reference_no', $quote_id)->first();
    	                            if(isset($delievery_challan)):
    	                                $delievery_challan_no = $delievery_challan->id;
    	                            else:
    	                                $delievery_challan_no = '';
    	                            endif;
                                else:
                                    $delievery_challan_no = '';
                                endif;
                            endif;
							?>
							<div class="form-group col-md-3">
							    <label>Delievery Challan No.</label>
								<input type="text" class="form-control" id="delievery_challan_no" name="delievery_challan_no" value="{{ $delievery_challan_no }}" placeholder="Delievery Challan No" style="height: 30px!important;">
								<div class="help-block with-errors"></div>
							</div>
						</div>
							

							<div class="col-md-12">
							 	<h4 class="box-title">Walking Customer Details</h4>
							</div>
						 
						 	<div class="col-md-12">
							 	<h4 class="box-title">Customer Details</h4>
							</div>
						 	<input type="hidden" name="walkCustomer" value="{{ (isset($customer)) ? $customer->id : '' }}">
						 	<?php
						 	if(isset($customer)):
						    if($customer->type == 'walkin'):
						        $customer_name  = $customer->customer_name;
						        $phone_no       = $customer->phone_no;
						        $email          = $customer->email;
						        $address        = $customer->address;
						        $city           = $customer->city;
						        $company_name   = $customer->company_name;
						    else:
						        $customer_name  = '';
						        $phone_no       = '';
						        $email          = '';
						        $address        = '';
						        $city           = '';
						        $company_name   = '';
						    endif;
						    else:
						        $customer_name  = '';
						        $phone_no       = '';
						        $email          = '';
						        $address        = '';
						        $city           = '';
						        $company_name   = '';
						    endif;
						 	?>
							
							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company_name }}" placeholder="Company Name">
								<div class="help-block with-errors"></div>
							</div>
							

							<div class="form-group col-md-4">
								<input type="number" class="form-control" id="phone_no" name="phone_no" value="{{ $phone_no }}" placeholder="Phone No">
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-4">
								<input type="email" class="form-control" id="email" name="email" value="{{ $email }}" placeholder="Email Address">
								<div class="help-block with-errors"></div>
							</div>

						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="address" name="address" value="{{ $address }}" placeholder="Address">
								<div class="help-block with-errors"></div>
						 	</div>

							<div class="form-group col-md-4">
								<input type="text" class="form-control" id="city" name="city" value="{{ $city }}" placeholder="City">
								<div class="help-block with-errors"></div>
							</div>
							
						 	<div class="form-group col-md-4">
								<input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $customer_name }}" placeholder="Customer Name">
								<div class="help-block with-errors"></div>
							</div>
							
						  	
						  	<!--if(Auth::User()->designation == 'Super Admin' OR Auth::User()->designation == 'Branch Admin')-->
						  	<div class="form-group col-md-4">
								<input type="checkbox" id="sales_tax_invoice" name="sales_tax_invoice" <?php echo ($Invoice->sales_tax_invoice == 'on') ? 'checked' : '' ?>> Sales Tax Invoice
								<div class="help-block with-errors"></div>
							</div>
							<!--endif-->
						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Generate Invoice</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>


<!-- The Investory Modal -->
<div class="modal" id="investoryModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Inventory</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <!-- Modal body -->
      <div class="modal-body"></div>
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<footer class="footer">
	<ul class="list-inline">
		<li>{{ date('Y') }} © Al Akhzir Tech.</li>
		
	</ul>
	<footer class="footer">
	<ul class="list-inline">
		<li>{{ date('Y') }} © Al Akhzir Tech.</li>
		
	</ul>
</footer>
</div>
</div>
<style>
.txtdescription_wrapper {
	display:none;
}	
</style>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script>

$("#terms").on('change', function(){
	var terms = $('#terms').find(":selected").val();
	//tinyMCE.activeEditor.setContent(terms);
	tinyMCE.get('txtarea_terms').setContent(terms);
	
});


$( document ).ready(function() {
	
	tinyMCE.init({
        selector: '.txtdescription'
      });
	    
	  // Function to update sequence numbers
		function updateSequenceNumbers() {
        $('input[name="sequence[]"]').each(function (index) {
            $(this).val(index + 1);
        });
    }

	  //$('textarea').hide();

	$("#customerTable").on('click', '.btnDelete', function () {
	    $(this).closest('tr').prev('tr').remove();
	    $(this).closest('tr').next('tr').remove();
	    $(this).closest('tr').remove();
		updateSequenceNumbers();
	});
	
	var i=<?php echo $sequence; ?>;
	$("#customerTable").on('click', '.addmore', function(){
		var data='<tr><th colspan="6"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';
		
	    data +='<td class="productTD"><select class="form-control select2_1 productName product_id"  id="product_id_'+i+'" required name="product_id[]" style="height: 40px!important;width:100%">';
	    data +='<option value="">Select Product</option>';
	    @if($products)
	    @foreach($products as $product);
	    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
	    @endforeach 
	    @endif 
	    data +='</select></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"  required></td>';

	    data +='<td class="productCapacityTD"><select class="form-control select2_1 productCapacity" id="productCapacity_'+i+'" name="productCapacity[]" style="height: 30px!important;"></select></td>';

        data +='<td><input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;"></td>';

	    data +='<td style="width: 20%;">';
	    data +='<a class="btn btn-primary btn-xs waves-effect waves-light addmore2" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data += '&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';
	    
	    data +='</tr>';

	    data +='<tr data-trid="'+i+'">'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>';
		data +='<div  class="form-group col-md-2" style="text-align: left;padding: 10;px">';
		data +='<input type="number" step="0.01" class="form-control" id="gst_product" name="gst_product[]" value="" placeholder="GST Product"  style="height: 30px!important;">';
		data +='</div>';
		data += '<div class="txtdescription_wrapper">';
		data +='<textarea id="tmce_'+i+'" name="description[]" class="form-control description txtdescription" placeholder="Description" style="height: 50px"></textarea>';
		data += '</div>';
		data +='</th>';
		data +='</tr>';
		
	    //$('table#customerTable').prepend(data);
	    $(this).parent().parent().parent().next('tr').after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
		tinyMCE.init({
        	selector: '.txtdescription'
      	});
		  updateSequenceNumbers();
	    i++;
	});


	// this is for run time added plus button
	$("#customerTable").on('click', '.addmore2', function(){
		var data='<tr><th colspan="6"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
		data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';
		
	    data +='<td class="productTD"><select class="form-control select2_1 productName product_id"  id="product_id_'+i+'" required name="product_id[]" style="height: 40px!important;width:100%">';
	    data +='<option value="">Select Product</option>';
	    @if($products)
	    @foreach($products as $product);
	    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
	    @endforeach 
	    @endif 
	    data +='</select></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"  required></td>';

	    data +='<td class="productCapacityTD"><select class="form-control select2_1 productCapacity" id="productCapacity_'+i+'" name="productCapacity[]" style="height: 30px!important;"></select></td>';

        data +='<td><input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;"></td>';

	    data +='<td style="width: 20%;">';
	    data +='<a class="btn btn-primary btn-xs waves-effect waves-light addmore2" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data +='&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';
	    data +='</tr>';

		data +='<tr data-trid="'+i+'">'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>';
		data +='<div  class="form-group col-md-2" style="text-align: left;padding: 10;px">';
		data +='<input type="number" step="0.01" class="form-control" id="gst_product" name="gst_product[]" value="" placeholder="GST Product"  style="height: 30px!important;">';
		data +='</div>';
		data += '<div class="txtdescription_wrapper">';
		data +='<textarea id="tmce_'+i+'" name="description[]" class="form-control description txtdescription" placeholder="Description" style="height: 50px"></textarea>';
		data += '</div>';
		data +='</th>';
		data +='</tr>';
		
	    //$('table#customerTable').prepend(data);
	    $(this).parent().parent().next('tr').after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
		tinyMCE.init({
        	selector: '.txtdescription'
      	});
		  updateSequenceNumbers();
	    i++;
	});
});

$( document ).ready(function() {

	$("#productsTable").on('click', '.productBtnDelete', function () {
	    $(this).closest('tr').prev('tr').remove();
	    $(this).closest('tr').remove();
	});

	var i=<?php echo $sequence_2+1; ?>;
	// this is for default plus button
	$(".addmoreProduct").on('click',function(){
		var data='<tr><th colspan="7"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';

	    data +='<td><input type="text" class="form-control" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 30px!important;"  required></td>'; 
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;"  required></td>'; 

	    data +='<td>';

	    data += '<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct2" style="color: #fff"><i class="fa fa-plus"></i></a>&nbsp;';
	    data += '<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';							
	    data += '</td>';

	    data +='</tr>';
	    
	    //$('table#productsTable').prepend(data);
	    $(this).parent().parent().parent().after(data);
	    i++;
	});

	$("#productsTable").on('click',  '.addmoreProduct2', function(){
		var data='<tr><th colspan="7"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';

	    data +='<td><input type="text" class="form-control" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	     data +='<td><input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 30px!important;"  required></td>'; 
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;"  required></td>'; 

	    data +='<td>';

	    data += '<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct2" style="color: #fff"><i class="fa fa-plus"></i></a>&nbsp;';
	    data += '<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';							
	    data += '</td>';

	    data +='</tr>';
	    
	    //$('table#productsTable').prepend(data);
	    $(this).parent().parent().after(data);
	    i++;
	});

});

// ------------------------------------------------------------------------------------------------------------------------------------ //

</script>
@endsection