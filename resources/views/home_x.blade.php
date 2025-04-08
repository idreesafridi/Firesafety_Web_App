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
<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-6">
					<div class="box-content table-responsive">
						<h3>Month Sales</h3>
						<h1>Rs: {{$total}}</h1>
					</div>
				</div>

				<?php 
				$Expense = App\Models\Expense::select('id')->whereMonth('dated',  date('m'))->whereYear('dated',  date('Y'))->sum('amount');
				?>
				<div class="col-6">
					<div class="box-content table-responsive">
						<h3>Month Expense</h3>
						<h1>Rs: {{$Expense}}</h1>
					</div>
				</div> 
			</div>	

			<div class="row small-spacing">
				<?php 
				$ExpiredInvoices = App\Models\Invoice::where('refill_notification', 'on')->where('expiry_date', date('Y-m-d'))->latest()->get();
				?>
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
									<th>Comapny Name</th>
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
									<th>Comapny Name</th>
									<th>Total Amount</th>
									<th>Paid Amount</th>
									<th>Sales Tax Invoice</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($ExpiredInvoices)
									@foreach($ExpiredInvoices as $Invoice)
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
											<td>{{  $Invoice->total_amount }}</td>
											<td>{{ $Invoice->paid_amount }}</td>
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
											    <a href="{{ route('downloadInvoice',$Invoice->id) }}" style="display: inline-block;color: #000" target="_blank">
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
			<footer class="footer">
				<ul class="list-inline">
					<li>2020 © Al Akhzir Tech.</li>
				</ul>
			</footer>
		</div>
		<!-- /.main-content -->
</div>
@endif

@endsection
