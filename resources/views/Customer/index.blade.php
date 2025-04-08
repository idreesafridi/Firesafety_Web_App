@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Customer</h1>
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
									<th>Company Name</th>
									<th>Customer Name</th>
									<th>Address</th>
									<th>Phone Number</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Company Name</th>
									<th>Customer Name</th>
									<th>Address</th>
									<th>Phone Number</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Customers)
									@foreach($Customers as $Customer)
										<?php 
										$customersData = App\Models\Customer::where('company_name', $Customer->company_name)->get();
										?>
										<tr>
											<td>{{ $Customer->company_name }}</td>
											
											<td>
												@foreach($customersData as $customersD)
													{{ $customersD->customer_name }} <br>
												@endforeach
											</td>
											<td>
												@foreach($customersData as $customersD)
													{{ $customersD->address }} <br>
												@endforeach
											</td>
											<td>
												@foreach($customersData as $customersD)
													{{ $customersD->phone_no }} <br>
												@endforeach
											</td>
											<?php $CustomerData  = App\Models\Customer::where('company_name', $Customer->company_name)->first(); ?>
											<td>
												<a href="{{ route('Customer.show', $CustomerData->id) }}" style="display: inline-block;color: #000">
													<i class="menu-icon fa fa-eye"></i>
												</a>
												<a href="{{ route('Customer.edit', $CustomerData->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Customer.destroy', $CustomerData->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Customer?');" style="display: inline-block;background: transparent; border:none;">
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

@endsection
