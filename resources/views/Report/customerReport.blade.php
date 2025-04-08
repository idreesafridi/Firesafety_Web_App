@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Customer Report</h1>
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
							<form action="{{ route('customerReport') }}" method="GET" class="form-horizontal">
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
									<th>Customer Name</th>
									<th>Address</th>
									<th>Phone Number</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Customer Name</th>
									<th>Address</th>
									<th>Phone Number</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($reports)
									@foreach($reports as $Customer)
										<tr>
											<td>{{ $Customer->customer_name }}</td>
											<td>{{ $Customer->address }}</td>
											<td>{{ $Customer->phone_no }}</td>
											<td>
												<a href="{{ route('Customer.show',$Customer->id) }}" style="display: inline-block;color: #000">
													<i class="menu-icon fa fa-eye"></i>
												</a>
												<a href="{{ route('Customer.edit',$Customer->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Customer.destroy', $Customer->id) }}" method="POST" style="display: inline-block;">
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
</div>

@endsection
