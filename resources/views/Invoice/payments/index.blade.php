@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Invoice Payments</h1>
    </div>
</div>

<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content table-responsive">
						<table class="table table-striped table-bordered display" style="width:100%">
							<tbody>
								<tr>
									<th>Invoice #</th>
									<td>{{ $invoice->invoice_no }}</td>
								</tr>
								<tr>
									<th>Customer Name</th>
									<td>{{ $invoice->customer->customer_name }}</td>
								</tr>
								<tr>
									<th>Customer Phone</th>
									<td>{{ $invoice->customer->phone_no }}</td>
								</tr>
									<th>Company Name</th>
									<td>{{ $invoice->customer->company_name }}</td>
								</tr>
								<tr>
									<th>Total Amount</th>
									<td>Rs {{ $totalAmount }}</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example2" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>Recieved Date</th>
									<th>Recieved By</th>
									<th>Payment Mode</th>
									<th>Amount Recieved</th>
									<th>WH Tax Deduct</th>
									<th>Sales Tax Deduct</th>
									<th>Total Tax Deducted</th>
									<th>Update</th>
								</tr>
							</thead>
							<tbody>
								@if($invoice->payments)
									@foreach($invoice->payments as $payment)
										<tr>
											<td>{{ date("d M, Y", strtotime($payment->dated)) }}</td>
											<td>{{ $payment->recievedby->username }}</td>
											<td>{{ $payment->payment_mode }}</td>
											<td>Rs {{ number_format($payment->amount_recieved, 2) }}</td>
											<td>Rs {{ number_format($payment->wh_tax, 2) }}</td>
											<td>Rs {{ number_format($payment->sales_tax, 2) }}</td>
											<td>Rs {{ number_format($payment->wh_tax+$payment->sales_tax, 2) }}</td>
											<td> 
											    <a href="{{ route('invocePayment.update', $payment->id) }}" class="btn btn-sm btn-primary"> 
											        <i class="fa fa-edit"></i> update
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
