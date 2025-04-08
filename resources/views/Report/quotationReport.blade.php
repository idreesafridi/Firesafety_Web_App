@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Quotation Report</h1>
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
							<form action="{{ route('quotationReport') }}" method="GET" class="form-horizontal">
								<div class="form-group row">
									<div class="col-sm-3">
										<div class="input-group">
											<input type="date" class="form-control" name="from_date" id="datepicker-autoclose">
										</div>
									</div>
									<div class="col-sm-3">
										<div class="input-group">
											<input type="date" class="form-control" name="to_date" id="datepicker-autoclose">
										</div>
									</div>
									<div class="form-group col-md-2">
										<input type="text" class="form-control select2_1" name="name" placeholder="Name">
									</div>
									<div class="form-group col-md-2">
										<select class="form-control select2_1" name="designation">
											<option value="">Designation</option>
											<option value="Super Admin">Super Admin</option>
											<option value="Branch Admin">Branch Admin</option>
											<option value="Staff">Staff</option>
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
				<div class="box-content table-responsive" >
					<table id="example" class="table table-striped table-bordered display" style="width:100%">
						<thead>
							<tr>
								<th>Date</th>
								<th>Quote Number</th>
								<th>Customer Name</th>
								<th>Customer Phone</th>
								<th>Comapny Name</th>
								<th>Total Amount</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Date</th>
								<th>Quote Number</th>
								<th>Customer Name</th>
								<th>Customer Phone</th>
								<th>Comapny Name</th>
								<th>Total Amount</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<tbody>
							@if($reports)
								@foreach($reports as $Quote)
									<tr>
										<td>{{ date("d M, Y", strtotime($Quote->dated)) }}</td>
										<td>{{ $Quote->id }}</td>
										<?php $customer = App\Models\Customer::find($Quote->customer_id); ?>
										<td>{{ $customer->customer_name }}</td>
										<td>{{ $customer->phone_no }}</td>
										<td>{{ $customer->email }}</td>
										<?php 
										$QouteProducts = App\Models\QouteProducts::where('quote_id', $Quote->id)->get(); 
										$qty = 0;
										$unitPrice = 0;
										foreach($QouteProducts as $QProduct):
											$qty       += $QProduct->qty;
											$unitPrice += $QProduct->unit_price;
										endforeach;
										?>
										<td>{{  $qty*$unitPrice }}</td>
										<td>
											<a href="{{ route('Quotes.show',$Quote->id) }}" style="display: inline-block;color: #000">
												<i class="menu-icon fa fa-eye"></i>
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
</div>

@endsection
