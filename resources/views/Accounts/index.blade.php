@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Payments</h1>
    </div>
</div>

<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>Date</th>
									@if(Auth::User()->designation == 'Super Admin')
									<th>Staff Name</th>
									@endif
									<th>Invoice No</th>
									<th>Customer Name</th>
									<th>Amount Recieved</th>
									<th>Comments</th>
									<th>Verified</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									@if(Auth::User()->designation == 'Super Admin')
									<th>Staff Name</th>
									@endif
									<th>Invoice No</th>
									<th>Customer Name</th>
									<th>Amount Recieved</th>
									<th>Comments</th>
									<th>Verified</th>
								</tr>
							</tfoot>
							<tbody>
								@if($payments)
									@foreach($payments as $payment)
										<tr>
											<td>{{ $payment->dated }}</td>
											@if(Auth::User()->designation == 'Super Admin')
											<?php $staff = App\Models\User::find($payment->recieved_by); ?>
											<td>{{ $staff->username }}</td>
											@endif
											<td>{{ $payment->invoice_id }}</td>
											<?php 
											$invoice = App\Models\Invoice::find($payment->invoice_id);  
											$customer = App\Models\Customer::find($invoice->customer_id);
											?>
											<td>{{ $customer->customer_name }}</td>
											<td>{{ $payment->amount_paid }}</td>
											<td>{{ $payment->comments }}</td>
											@if(Auth::User()->designation == 'Super Admin' AND $payment->verified == 'No')
											<td><a href="{{ route('VerifyPaymentNow', $payment->id) }}">Verify Now</a></td>
											@else
											<td>{{ $payment->verified }}</td>
											@endif
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
