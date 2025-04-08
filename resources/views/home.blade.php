@extends('layouts.app')
@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Welcome, {{ Auth::User()->username }}</h1>
    </div>
</div>

<?php $myRights = explode(', ', Auth::User()->rights); ?>
@if(Auth::User()->designation == 'Staff' AND in_array('Invoice', $myRights))
E
<?php $Invoices = App\Models\Invoice::where('user_id', Auth::User()->id)->latest()->get(); ?>
<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>Date</th>
									<th>Invoice Number</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Comapny Name</th>
									<th>Total Amount</th>
									<th>Sales Tax Invoice</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									<th>Invoice Number</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Comapny Name</th>
									<th>Total Amount</th>
									<th>Sales Tax Invoice</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Invoices)
									@foreach($Invoices as $Invoice)
										<tr>
											<td>{{ date("d M, Y", strtotime($Invoice->dated)) }}</td>
											<td>{{ $Invoice->id }}</td>
											<?php $customer = App\Models\Customer::find($Invoice->customer_id); ?>
											<td>{{ $customer->customer_name }}</td>
											<td>{{ $customer->phone_no }}</td>
											<td>{{ $customer->email }}</td>
											<?php 
											$InvoiceProducts = App\Models\InvoiceProducts::where('invoice_id', $Invoice->id)->get(); 
											$qty = 0;
											$unitPrice = 0;
											foreach($InvoiceProducts as $InvoiceProduct):
												$qty       += $InvoiceProduct->qty;
												$unitPrice += $InvoiceProduct->unit_price;
											endforeach;
											?>
											<td>{{  $qty*$unitPrice }}</td>
											<td>
												@if($Invoice->sales_tax_invoice == 'on')
												<a target="_blank" href="{{ route('SalesTaxInvoice', $Invoice->id) }}">View</a>
												@else
												N/A
												@endif
											</td>
											<td>
												<a href="{{ route('Invoice.show',$Invoice->id) }}" style="display: inline-block;color: #000">
													<i class="menu-icon fa fa-eye"></i>
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
@else
@php 
$from_month = (isset($from_month)) ? $from_month : request('from'); // Changed 'month' to 'from'
$to_month = (isset($to_month)) ? $to_month : request('to'); // Changed 'month' to 'to'
$year = (isset($year)) ? $year : request('year'); 
@endphp
<div id="wrapper">
    <div class="main-content">
        <div class="row small-spacing">
            <div class="col-xl-12 col-12">
              <div class="box-content">
              <form action="{{ route('summary') }}" method="get">
                <div class="row small-spacing mb-3">
                    <div class="col-xl-8">
                        <h4 class="box-title">Summary</h4>
                    </div>
                    <div class="col-xl-2 col-xs-2" style="border: 1px solid #d1d1d1;padding: 5px;">
                        <label for="from" style="margin-bottom:0px;">From Month</label>
                        <input type="month" name="from" id="from" class="form-control" value="{{ $from_month }}" required>
                    </div>
                    <div class="col-xl-2 col-xs-2" style="border: 1px solid #d1d1d1;padding: 5px;">  
                        <label for="to" style="margin-bottom:0px;">To Month</label>
                        <input type="month" name="to" id="to" class="form-control" value="{{ $to_month }}">
                    </div>
                    <!-- Button to submit the form -->
                    <div class="col-xl-12 text-right mt-3">
                      <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                  </div>
                </div>
            </form>
                    <div class="row small-spacing">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <a href="{{ route('stats.invoices', ['from'=>$from_month, 'to'=>$to_month]) }}" target="_blank">
                                        <i class="ico fas fa-chart-area text-info"></i>
                                        <h4 class="counter text-info">{{ $noOfInvoices }}</h4>
                                        <p class="text">No Of Invoices</p>
                                    </a>
                                </div>
                            </div>
                            <!-- /.box-content -->
                        </div>

                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <a href="{{ route('stats.invoices', ['from'=>$from_month, 'to'=>$to_month]) }}" target="_blank">
                                        <i class="ico fas fa-chart-area text-primary"></i>
                                        <h5 class="counter text-primary">No: {{ number_format($invoicesSalescount, 2) }}</h5>                                        
                                        <h4 class="counter text-primary">Rs: {{ number_format($invoicesSales, 2) }}</h4>
                                        <p class="text">Total Invoices Sales</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <a href="{{ route('stats.cashmemo', ['from'=>$from_month, 'to'=>$to_month]) }}" target="_blank">
                                        <i class="ico fas fa-chart-area text-info"></i>
                                        <h5 class="counter text-info">No: {{ number_format($cashSalescount, 2) }}</h5>  
                                        <h4 class="counter text-info">Rs: {{ number_format($cashSales, 2) }}</h4>
                                        <p class="text">Total Cash Sales</p>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <i class="ico fas fa-chart-area text-primary"></i>
                                    <h4 class="counter text-primary">Rs: {{ number_format($totalSales, 2) }}</h4>
                                    <p class="text">Total Sales</p>
                                </div>
                            </div>
                        </div>
                        <!-- /.col-xl-3 col-lg-6 col-12 -->
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <a href="{{ route('pending.invoices', ['from'=>$from_month, 'to'=>$to_month]) }}" target="_blank">
                                        <i class="ico fas fa-chart-area text-warning"></i>
                                        
                                        <h5 class="counter text-warning">No: {{ number_format($pendingInvoicescount, 2) }}</h5>
                                        <h4 class="counter text-warning">Rs: {{ number_format($pendingInvoices, 2) }}</h4>
                                        <p class="text">Total Pending Invoices</p>
                                    </a>
                                </div>
                            </div>
                            <!-- /.box-content -->
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <a href="{{ route('clear.invoices', ['from'=>$from_month, 'to'=>$to_month]) }}" target="_blank">
                                    <i class="ico fas fa-chart-area text-success"></i>
                                    <h5 class="counter text-warning">No: {{ number_format($clearedInvoicecount, 2) }}</h5>
                                        <h4 class="counter text-success">Rs: {{ number_format($clearInvoices, 2) }}</h4>
                                        <p class="text">Total Clear Invoices</p>
                                    </a>
                                </div>
                            </div>
                            <!-- /.box-content -->
                        </div>
                        <!-- /.col-xl-3 col-lg-6 col-12 -->
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <a href="{{ route('expenses', ['from'=>$from_month, 'to'=>$to_month]) }}" target="_blank">
                                        <i class="ico fas fa-chart-area text-danger"></i>
                                        <h4 class="counter text-danger">Rs {{ number_format($totalExpenses, 2) }}</h4>
                                        <p class="text">Total Expenses</p>
                                    </a>
                                </div>
                            </div>
                            <!-- /.box-content -->
                        </div>
                        <!-- /.col-xl-3 col-lg-6 col-12 -->
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="box-content">
                                <div class="statistics-box with-icon">
                                    <i class="ico fas fa-chart-area text-success"></i>
                                    <h4 class="counter text-success">Rs: {{ number_format($totalSales-$totalExpenses, 2) }}</h4>
                                    <p class="text">Net Profit/Loss</p>
                                </div>
                            </div>
                            <!-- /.box-content -->
                        </div>
                        <!-- /.col-xl-3 col-lg-6 col-12 -->
                    </div>
                </div>
            </div>
        </div>

        <div class="row small-spacing">
            <div class="col-12">
                <div class="box-content table-responsive">
                    <h3>Expire Invoices</h3>
                    <br>
                    <table id="example" class="table table-striped table-bordered display" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Invoice Number</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th>Company Name</th>
                                <th>Total Amount</th>
                                <th>Paid Amount</th>
                                <th>Sales Tax Invoice</th>
                                <th>Action</th>
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
                                <th>Paid Amount</th>
                                <th>Sales Tax Invoice</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if($ExpiredInvoices->count() > 0)
                                @foreach($ExpiredInvoices as $Invoice)
                                    <tr>
                                        <td>{{ date("d M, Y", strtotime($Invoice->dated)) }}</td>
                                        <td>{{ $Invoice->invoice_no}}</td>
                                        <?php
                                          $customer = App\Models\Customer::find($Invoice->customer_id);

                                          if(isset($customer)) {
                                              echo '<td>' . $customer->customer_name . '</td>';
                                              echo '<td>' . $customer->phone_no . '</td>';
                                              echo '<td>' . $customer->company_name . '</td>';
                                          } else {
                                              // Handle the case where customer is not found
                                              // You can echo an error message or provide a default value
                                              echo '<td>Customer not found</td>';
                                              echo '<td>N/A</td>';
                                              echo '<td>N/A</td>';
                                          }
                                          ?>
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
                                                $qty            =  $moreProductsQty[$count2]; 
                                                $price          =  $moreProductsPrice[$count2];
                                                if(is_numeric($qty) && is_numeric($price)):
                                                $totalPrice2    += $qty*$price; 
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
                                        <td>{{ number_format($netTotal, 2) }}</td>
                                        <td>{{ number_format($Invoice->paid_amount, 2) }}</td>
                                        <td>
                                            @if($Invoice->sales_tax_invoice == 'on')
                                            <a target="_blank" href="{{ route('SalesTaxInvoice', $Invoice->id) }}">View</a>
                                            &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; 
                                            <a href="{{ route('downloadSalesInvoice',$Invoice->id) }}" style="display: inline-block;color: #000" target="_blank">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                            </a>
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('Invoice.show',$Invoice->invoice_no) }}" style="display: inline-block;color: #000" target="_blank">
                                                <i class="fa fa-download" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ route('Invoice.show',$Invoice->id) }}" style="display: inline-block;color: #000">
                                                <i class="menu-icon fa fa-eye"></i>
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
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content">
						<h4 class="box-title">Revenue from Sales</h4>
						<!-- /.box-title -->
						<canvas id="myChart" style="width:100%;max-width:100%;height: 350px;"></canvas>
						<!-- /#smil-animation-chartist-chart.chartist-chart -->
					</div>
				</div>
			</div>
            
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content">
						<h4 class="box-title">Sales by Products</h4>
						
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active show" id="invoiceqty-tab" data-toggle="tab" href="#invoiceqty" role="tab" aria-controls="invoiceqty" aria-selected="true">Invoices Sales (Qty)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="invoiceamount-tab" data-toggle="tab" href="#invoiceamount" role="tab" aria-controls="invoiceamount" aria-selected="false">Invoices Sales (Sales)</a>
							</li>
							
							<li class="nav-item">
								<a class="nav-link" id="cashmemoqty-tab" data-toggle="tab" href="#cashmemoqty" role="tab" aria-controls="cashmemoqty" aria-selected="true">Cash Memo Sales (Qty)</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="cashmemoamount-tab" data-toggle="tab" href="#cashmemoamount" role="tab" aria-controls="cashmemoamount" aria-selected="false">Cash Memo (Sales)</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade active show" id="invoiceqty" role="tabpanel" aria-labelledby="invoiceqty-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByProductQty" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
							<div class="tab-pane fade" id="invoiceamount" role="tabpanel" aria-labelledby="invoiceamount-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByProduct" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
							
							<div class="tab-pane fade" id="cashmemoqty" role="tabpanel" aria-labelledby="cashmemoqty-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByProductQtyCM" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
							<div class="tab-pane fade" id="cashmemoamount" role="tabpanel" aria-labelledby="cashmemoamount-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByProductCM" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
						</div>
					</div>
				</div>

				<div class="col-12">
					<div class="box-content">
						<h4 class="box-title">Sales by Customers</h4>
	
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active show" id="salesBCInv-tab" data-toggle="tab" href="#salesBCInv" role="tab" aria-controls="salesBCInv" aria-selected="true">Invoices Sales</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="salesBCCM-tab" data-toggle="tab" href="#salesBCCM" role="tab" aria-controls="salesBCCM" aria-selected="false">Cash Memo Sales</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade active show" id="salesBCInv" role="tabpanel" aria-labelledby="salesBCInv-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByCustomers" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
							<div class="tab-pane fade" id="salesBCCM" role="tabpanel" aria-labelledby="salesBCCM-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByCustomersCM" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="col-12">
					<div class="box-content">
						<h4 class="box-title">Sales by Category</h4>
			
						<ul class="nav nav-tabs" id="myTab" role="tablist">
							<li class="nav-item">
								<a class="nav-link active show" id="salesByCategoryInv-tab" data-toggle="tab" href="#salesByCategoryInv" role="tab" aria-controls="salesByCategoryInv" aria-selected="true">Invoices Sales</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" id="salesByCategoryCM-tab" data-toggle="tab" href="#salesByCategoryCM" role="tab" aria-controls="salesByCategoryCM" aria-selected="false">Cash Memo Sales</a>
							</li>
						</ul>
						<div class="tab-content" id="myTabContent">
							<div class="tab-pane fade active show" id="salesByCategoryInv" role="tabpanel" aria-labelledby="salesByCategoryInv-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByCategory" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
							<div class="tab-pane fade" id="salesByCategoryCM" role="tabpanel" aria-labelledby="salesByCategoryCM-tab">
								<div style="height: 450px;overflow-y: scroll;">
        						    <div id="chartSalesByCategoryCM" style="width:100%;max-width:100%;"></div>
        						</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content">
						<h4 class="box-title">Products Inventory</h4>
						
						<table id="example2" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>S:No</th>
									<th>Product Name</th>
									<th>Inventory</th>
								</tr>
							</thead>
							<tbody>
								@if($products)
									@foreach($products as $product)
										<tr>
											<td>{{ $loop->count - $loop->iteration + 1 }}</td>
											<td>{{ $product->name }}</td>
											<td>{{ $product->inventory }}</td>
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


<!-- Revenue by Sales Chart -->
<script src="{{ asset('assets/scripts/jquery.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<?php
$newYear = (isset($year))?(int)$year:date('Y');
$heighet = getMonthHighestSales($newYear);
?>
<script type="text/javascript">
	const xValues = ['January','February','March','April','May','June','July','August','September','October','November', 'December'];
	const yValues = [{{ getRevenuesByMonthYear(1, $year)+getRevenuesByMonthYearCM(1, $year) }}, {{ getRevenuesByMonthYear(2, $year)+getRevenuesByMonthYearCM(2, $year) }},{{ getRevenuesByMonthYear(3, $year)+getRevenuesByMonthYearCM(3, $year) }}, {{ getRevenuesByMonthYear(4, $year)+getRevenuesByMonthYearCM(4, $year) }}, {{ getRevenuesByMonthYear(5, $year)+getRevenuesByMonthYearCM(5, $year) }}, {{ getRevenuesByMonthYear(6, $year)+getRevenuesByMonthYearCM(6, $year) }}, {{ getRevenuesByMonthYear(7, $year)+getRevenuesByMonthYearCM(7, $year) }}, {{ getRevenuesByMonthYear(8, $year)+getRevenuesByMonthYearCM(8, $year) }}, {{ getRevenuesByMonthYear(9, $year)+getRevenuesByMonthYearCM(9, $year) }}, {{ getRevenuesByMonthYear(10, $year)+getRevenuesByMonthYearCM(10, $year) }}, {{ getRevenuesByMonthYear(11, $year)+getRevenuesByMonthYearCM(11, $year) }}, {{ getRevenuesByMonthYear(12, $year)+getRevenuesByMonthYearCM(12, $year) }}];
   
	//const CM = [{{ getRevenuesByMonthCM(1, $year) }}, {{ getRevenuesByMonthCM(2, $year) }},{{ getRevenuesByMonthCM(3, $year) }}, {{ getRevenuesByMonthCM(4, $year) }}, {{ getRevenuesByMonthCM(5, $year) }}, {{ getRevenuesByMonthCM(6, $year) }}, {{ getRevenuesByMonthCM(7, $year) }}, {{ getRevenuesByMonthCM(8, $year) }}, {{ getRevenuesByMonthCM(9, $year) }}, {{ getRevenuesByMonthCM(10, $year) }}, {{ getRevenuesByMonthCM(11, $year) }}, {{ getRevenuesByMonthCM(12, $year) }}];
	
	new Chart("myChart", {
	  type: "line",
	  data: {
	    labels: xValues,
	    datasets: [
    	    {
    	      fill: false,
    	      lineTension: 0,
    	      backgroundColor: "rgba(0,0,255,1.0)",
    	      borderColor: "rgba(0,0,255,0.1)",
    	      data: yValues
    	    }
	    ]
	  },
	  options: {
	    legend: {
	       display: false
	    },
	    
	    tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var value = data.datasets[0].data[tooltipItem.index];
                    if(parseInt(value) >= 1000){
                       var value = value.toFixed(2);
                       value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        
                        return 'Rs ' + value;
                    } else {
                       return 'Rs ' + value;
                    }
                }
            } // end callbacks:
        }, //end tooltips                
		
	    scales: {
	        yAxes: [
                {
                    stacked: true,
                    ticks: {
                        min: 0, 
                        callback: function(label, index, labels) {
                            return label/1000+'k';
                        },
                    },
                    scaleLabel: {
                        display: true,
                        labelString: '1k = 1000'
                    },
                    
                    beginAtZero:true,
                    callback: function(value, index, values) {
                        if(parseInt(value) >= 1000){
                            var value = value.toFixed(2);
                            value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            
                            return 'Rs ' + value;
                        } else {
                           return 'Rs ' + value;
                        }
                   }    
                }
            ]
	    }
	  }
	});
</script>
	
<!-- Sales by Product (Invoices) -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<div id="chartSalesByProduct"></div>

<script type="text/javascript">
  // Retrieve the sales data
  var salesData = [
    @if(SalesByProduct($from_month, $to_month, 100)->count() > 0)
      @foreach(SalesByProduct($from_month, $to_month, 100)->sortByDesc('amount')->take(50) as $product)
        {
          name: '{{ $product->name }}',
          amount: '{{ getInvoiceSalesByProduct($product->product_id, $from_month, ($to_month) ? $to_month : 'null') }}'
        },
      @endforeach
    @endif
  ];

  // Sort the sales data in descending order
  salesData.sort(function(a, b) {
    return b.amount - a.amount;
  });

  // Calculate the dynamic chart height based on the number of products
  var productCount = salesData.length;
  var maxChartHeight = 950; // Maximum height of the chart
  var minHeightPerProduct = 30;
  var chartHeight = Math.min(maxChartHeight, productCount * minHeightPerProduct); // Set the dynamic chart height

  var options = {
    series: [{
      name: "Amount: ",
      data: salesData.map(function(dataPoint) {
        return dataPoint.amount;
      })
    }],
    chart: {
      type: 'bar',
      height: chartHeight, // Set the dynamic chart height
      events: {
        dataPointSelection: function(event, chartContext, opts) {
          switch(opts.w.config.xaxis.categories[opts.dataPointIndex]) {
            @if(SalesByProduct($from_month, $to_month, 100)->count() > 0)
              @foreach(SalesByProduct($from_month, $to_month, 100) as $product)
                case '{{ $product->name }}':
                  window.open("{{ route('product.invoices', [$product->product_id,$from_month, ($to_month)?$to_month:'null']) }}");
                @endforeach
              @endif
          }
        }
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
      }
    },
    tooltip: {
      y: {
        show: true,
        formatter: function(val) {
          if (parseInt(val) >= 1000) {
            var val = val.toFixed(2);
            val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return 'Rs ' + val;
          } else {
            return 'Rs ' + val;
          }
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: function(val) {
        if (parseInt(val) >= 1000) {
          var val = val.toFixed(2);
          val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return 'Rs ' + val;
        } else {
          return 'Rs ' + val;
        }
      },
    },
    xaxis: {
      categories: salesData.map(function(dataPoint) {
        return dataPoint.name;
      }),
    },
  };

  var chart = new ApexCharts(document.querySelector("#chartSalesByProduct"), options);
  chart.render();
</script>

<!-- By Sales amount -->

<!-- Assuming you have a container with id "chartSalesByProductQty" for the chart -->
<div id="chartSalesByProductQty"></div>

<script type="text/javascript">
    // Fetch the sales data
    var salesData = [
        @if(SalesByProduct($from_month, $to_month)->count() > 0)
            @foreach(SalesByProduct($from_month, $to_month, 100)->sortByDesc('amount') as $product)
                { name: '{{ $product->name }}', total: '{{ $product->total }}' },
            @endforeach
        @endif
    ];

    // If there are fewer than 50 products, display all of them
    var maxProductsToShow = Math.min(50, salesData.length);
    var slicedSalesData = salesData.slice(0, maxProductsToShow);

    // Calculate the optimal chart height
    var barHeight = 32; // Set your desired fixed height for each bar (in pixels)
    var totalChartHeight = Math.max(barHeight * slicedSalesData.length, 300); // Minimum chart height of 300 pixels

    // Function to calculate the dynamic fill color based on the product quantity
    function getFillColor(value) {
        if (value >= 500) {
            return '#008FFB'; // Blue color for high quantity products
        } else {
            return '#008FFB'; // Gray color for other products
        }
    }

    // Prepare the options object
    var options = {
        series: [{
            name: "Qty: ",
            data: slicedSalesData.map(product => product.total)
        }],
        chart: {
            type: 'bar',
            height: totalChartHeight, // Set the chart height based on the total required height
            events: {
                dataPointSelection: function (event, chartContext, opts) {
                    switch (opts.w.config.xaxis.categories[opts.dataPointIndex]) {
                        @if(SalesByProduct($from_month, $to_month)->count() > 0)
                            @foreach(SalesByProduct($from_month, $to_month) as $product)
                                case '{{ $product->name }}':
                                    window.open("{{ route('product.invoices', [$product->product_id, $from_month, ($to_month) ? $to_month : 'null']) }}");
                                    break;
                            @endforeach
                        @endif
                    }
                }
            }
        },

        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
                colors: {
                    ranges: [
                        {
                            from: 0,
                            to: 300, // Set the range for partial fill (you can adjust this value)
                            color: getFillColor(300) // Color for the bars within the range (call the function with desired value)
                        },
                        {
                            from: 500, // Set a higher value to cover all other bars
                            to: 10000, // Set a value greater than the maximum value of your data
                            color: getFillColor(500) // Color for the bars outside the range (gray in this case, call the function with desired value)
                        }
                    ]
                }
            }
        },
        tooltip: {
            y: {
                show: true,
                formatter: function (val) {
                    if (parseInt(val) >= 1000) {
                        var val = val.toFixed(2);
                        val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        return val;
                    } else {
                        return val;
                    }
                },
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                if (parseInt(val) >= 1000) {
                    var val = val.toFixed(2);
                    val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return val;
                } else {
                    return val;
                }
            },
        },
        xaxis: {
            categories: slicedSalesData.map(product => product.name),
        },
    };

    var chart = new ApexCharts(document.querySelector("#chartSalesByProductQty"), options);
    chart.render();
</script>


 <!-- By Sales Qty -->


 <!-- Sales by Product (Cash Memo) -->
<script type="text/javascript">
    var data = [
        @if(CMSalesByProduct($from_month, $to_month, 100)->count() > 0)
        @foreach(CMSalesByProduct($from_month, $to_month, 100)->sortByDesc('sales_amount')->take(50) as $product)
        '{{ getCashMemoSales($product->cash_memo_id) }}',
        @endforeach
        @endif
    ];


    // Sort the data array in descending order
    data.sort(function(a, b) {
        return b - a;
    });

    var options = {
        series: [
        {
            name: "Amount: ",
            data: data,
        },
        ],
        chart: {
        type: 'bar',
        height: 300, // Set a fixed height to maintain consistent styling
        events: {
            dataPointSelection: function(event, chartContext, opts) {
            switch(opts.w.config.xaxis.categories[opts.dataPointIndex]) {
                @if(CMSalesByProduct($from_month, $to_month, 100)->count() > 0)
                @foreach(CMSalesByProduct($from_month, $to_month, 100)->sortByDesc('sales_amount') as $product)
                case '{{ $product->name }}':
                window.open("{{ route('product.cashmemos', [$product->product_id, $from_month, ($to_month) ? $to_month : 'null']) }}");
                @endforeach
                @endif
            }
            }
        }
        },
        plotOptions: {
        bar: {
            borderRadius: 4,
            horizontal: true,
            colors: {
                ranges: [
                    {
                        from: 0,
                        to: 50, // Set the range for partial fill (you can adjust this value)
                        color: getFillColor(50) // Color for the bars within the range (call the function with desired value)
                    },
                    {
                        from: 51, // Set a higher value to cover all other bars
                        to: 10000, // Set a value greater than the maximum value of your data
                        color: getFillColor(500) // Color for the bars outside the range (gray in this case, call the function with desired value)
                    }
                ]
            }
        }
    },
    tooltip: {
        y: {
            show: true,
            formatter: function (val) {
                if (parseInt(val) >= 1000) {
                    var val = val.toFixed(2);
                    val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return val;
                } else {
                    return val;
                }
            },
        },
    },

    dataLabels: {
      enabled: true,
      formatter: function (val) {
        if (parseInt(val) >= 1000) {
          var val = val.toFixed(2);
          val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return 'Rs ' + val;
        } else {
          return 'Rs ' + val;
        }
      },
    },
    xaxis: {
      categories: [
        @if(CMSalesByProduct($from_month, $to_month, 100)->count() > 0)
        @foreach(CMSalesByProduct($from_month, $to_month, 100)->sortByDesc('sales_amount') as $product)
        '{{ $product->name }}',
        @endforeach
        @endif
      ],
    },
  };

  var chart = new ApexCharts(document.querySelector("#chartSalesByProductCM"), options);
  chart.render();
</script>

<!-- SalesBy Sales amount -->
<script type="text/javascript">
  var productsData = {!! CMSalesByProduct($from_month, $to_month, 100)->sortByDesc('total')->toJson() !!};
  var data = productsData.slice(0, 50).map(product => product.total);

  var options = {
    series: [
      {
        name: "Qty: ",
        data: data,
      },
    ],
    chart: {
      type: 'bar',
      height: 300, // Set a fixed height for the chart
      events: {
        dataPointSelection: function(event, chartContext, opts) {
          var selectedProduct = productsData[opts.dataPointIndex];
          if (selectedProduct) {
            window.open("{{ route('product.cashmemos', [':product_id', $from_month, ($to_month) ? $to_month : 'null']) }}".replace(':product_id', selectedProduct.product_id));
          }
        }
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
        colors: {
          ranges: [
            {
              from: 0,
              to: 300, // Set the range for partial fill (you can adjust this value)
              color: '#008FFB' // Color for the bars within the range
            },
            {
              from: 500, // Set a higher value to cover all other bars
              to: 10000, // Set a value greater than the maximum value of your data
              color: '#d3d3d3' // Color for the bars outside the range (gray in this case)
            }
          ]
        }
      }
    },
    tooltip: {
      y: {
        show: true,
        formatter: function (val) {
          if (parseInt(val) >= 1000) {
            var val = val.toFixed(2);
            val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return val;
          } else {
            return val;
          }
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        if (parseInt(val) >= 1000) {
          var val = val.toFixed(2);
          val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return val;
        } else {
          return val;
        }
      },
    },
    xaxis: {
      categories: productsData.slice(0, 50).map(product => product.name), // Show only the top 50 products
    },
  };

  var chart = new ApexCharts(document.querySelector("#chartSalesByProductQtyCM"), options);
  chart.render();
</script>

<!-- By Sales Qty -->


<!-- Sales by Customers -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- By Invoice -->


<div id="chartSalesByCustomers"></div>
<!-- Add the necessary script -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts@3.28.3/dist/apexcharts.min.js"></script>

<script type="text/javascript">
  var data = [
    @if(SalesByCustomer($from_month, $to_month, 100)->count() > 0)
      @foreach(SalesByCustomer($from_month, $to_month, 100) as $customer)
      {
        x: '{{ customer($customer->customer_id)->company_name }}',
        y: '{{ getInvoiceSalesByCustomer($customer->customer_id, $from_month, ($to_month) ? $to_month : 'null') }}'
      },
      @endforeach
    @endif
  ];

  // Sort the data array in descending order based on y (amount)
  data.sort(function(a, b) {
    return b.y - a.y;
  });

  // Calculate the maximum value of "y" in the data array
  var maxY = Math.max(...data.map(item => item.y));

  // Set the height of the chart based on the maximum value of "y"
  var chartHeight = Math.min(750, Math.max(400, 50 * data.length));

  var options = {
    series: [{
      name: "Amount",
      data: data.map(item => item.y)
    }],
    chart: {
      type: 'bar',
      height: chartHeight,
      events: {
        dataPointSelection: function(event, chartContext, opts) {
          switch(opts.w.config.xaxis.categories[opts.dataPointIndex]) {
            @if(SalesByCustomer($from_month, $to_month, 100)->count() > 0)
              @foreach(SalesByCustomer($from_month, $to_month, 100) as $customer)
              case '{{ customer($customer->customer_id)->company_name }}':
                window.open("{{ route('customer.invoices', [$customer->customer_id, $from_month, ($to_month)?$to_month:'null']) }}");
                break;
              @endforeach
            @endif
          }
        }
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
      }
    },
    tooltip: {
      y: {
        formatter: function(val) {
          if (parseInt(val) >= 1000) {
            var val = val.toFixed(2);
            val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return 'Rs ' + val;
          } else {
            return 'Rs ' + val;
          }
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: function(val) {
        if (parseInt(val) >= 1000) {
          var val = val.toFixed(2);
          val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return 'Rs ' + val;
        } else {
          return 'Rs ' + val;
        }
      },
    },
    xaxis: {
      categories: data.map(item => item.x),
    }
  };

  var chart = new ApexCharts(document.querySelector("#chartSalesByCustomers"), options);
  chart.render();
</script>



<!-- Sales by Category -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<!-- By Invoice -->
<div id="chartSalesByCategory"></div>

<script type="text/javascript">
    // Sorting the data in descending order
    var sortedData = [
        @if($categories->count() > 0)
        @php
            $sortedCategories = $categories->sortByDesc(function($category) use ($from_month, $to_month) {
                return SalesByCategory($from_month, $to_month, $category->id);
            });
        @endphp
        @foreach($sortedCategories as $category)
        '{{ SalesByCategory($from_month, $to_month, $category->id) }}',
        @endforeach
        @endif
    ];

    var numCategories = sortedData.length;
    var barHeight = 30; // Set your desired fixed height for each bar (in pixels)
    var chartHeight = Math.max(barHeight * numCategories, 200); // Adjust the minimum height here

    var options = {
        series: [{
            name: "Amount: ",
            data: sortedData
        }],
        chart: {
            type: 'bar',
            height: chartHeight,
            events: {
                dataPointSelection: function(event, chartContext, opts) {
                    switch(opts.w.config.xaxis.categories[opts.dataPointIndex]) {
                        @if($categories->count() > 0)
                        @foreach($categories as $category)
                        case '{{ $category->name }}':
                            window.open("{{ route('category.invoices', [$category->id, $from_month, ($to_month)?$to_month:'null']) }}");
                            break;
                        @endforeach
                        @endif
                    }
                }
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 4,
                horizontal: true,
                dataLabels: {
                    position: 'center' // Place the data labels at the center of the bars
                },
                colors: {
                    ranges: [
                        {
                            from: 0,
                            to: 10000, // Set the range to cover all bars
                            color: '#008FFB' // Color for the bars
                        }
                    ]
                }
            }
        },
        tooltip: {
            y: {
                show: true,
                formatter: function (val) {
                    if(parseInt(val) >= 1000){
                        var val = val.toFixed(2);
                        val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        return 'Rs ' + val;
                    } else {
                        return 'Rs ' + val;
                    }
                },
            },
        },
        dataLabels: {
            enabled: true,
            formatter: function (val) {
                if(parseInt(val) >= 1000){
                    var val = val.toFixed(2);
                    val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return 'Rs ' + val;
                } else {
                    return 'Rs ' + val;
                }
            },
        },
        xaxis: {
            categories: [
                @if($categories->count() > 0)
                @foreach($sortedCategories as $category)
                '{{ $category->name }}',
                @endforeach
                @endif
            ],
        }
    };

    var chart = new ApexCharts(document.querySelector("#chartSalesByCategory"), options);
    chart.render();
</script>

<!-- By Cash Memo -->
<script type="text/javascript">
  // Assuming your data is already available in the following format
  var categoriesData = [
    @if($categories->count() > 0)
    @foreach($categories as $category)
      {
        name: '{{ $category->name }}',
        value: @php $SalesByCat = SalesByCategoryCM($from_month, $to_month, $category->id); echo $SalesByCat; @endphp
      },
    @endforeach
    @endif
  ];

  // Sort the data in descending order based on the 'value' field
  categoriesData.sort((a, b) => b.value - a.value);

  // Take only the top 50 products
  var top50Categories = categoriesData.slice(0, 50);

  var options = {
    series: [{
      name: "Amount: ",
      data: top50Categories.map(category => category.value),
    }],
    chart: {
      type: 'bar',
      height: 350,
      events: {
        dataPointSelection: function(event, chartContext, opts) {
          switch(opts.w.config.xaxis.categories[opts.dataPointIndex]) {
            @if($categories->count() > 0)
            @foreach($categories as $category)
              case '{{ $category->name }}':
                window.open("{{ route('category.cashmemos', [$category->id, $from_month, ($to_month)?$to_month:'null']) }}");
                break;
            @endforeach
            @endif
          }
        }
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        horizontal: true,
      }
    },
    tooltip: {
      y: {
        show: true,
        formatter: function (val) {
          if(parseInt(val) >= 1000){
            var val = val.toFixed(2);
            val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return 'Rs ' + val;
          } else {
            return 'Rs ' + val;
          }
        },
      },
    },
    dataLabels: {
      enabled: true,
      formatter: function (val) {
        if(parseInt(val) >= 1000){
          var val = val.toFixed(2);
          val = val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
          return 'Rs ' + val;
        } else {
          return 'Rs ' + val;
        }
      },
    },
    xaxis: {
      categories: top50Categories.map(category => category.name),
    }
  };
  var chart = new ApexCharts(document.querySelector("#chartSalesByCategoryCM"), options);
  chart.render();
</script>

@endif
@endsection
