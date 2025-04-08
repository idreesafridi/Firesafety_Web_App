@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Delivery Challan</h1>
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
    							<form action="{{ route('filter-delivery-challan') }}" method="GET" class="form-horizontal">
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
						<table id="example2" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>Date</th>
									<th>Our Quote No</th>
									<th>Ref No</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Comapny Name</th>
									<th>Action</th>
									<th>Duplicate</th>
									<th>Excel</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Date</th>
									<th>Customer Order No</th>
									<th>Ref No</th>
									<th>Customer Name</th>
									<th>Customer Phone</th>
									<th>Comapny Name</th>
									<th>Action</th>
									<th>Duplicate</th>
									<th>Excel</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Challans)
									@foreach($Challans as $Challan)
										<tr>
											<td>{{ date("d M, Y", strtotime($Challan->created_date)) }}</td>
											<td>{{ ($Challan->reference_no) ? $Challan->reference_no : 'N/A' }}</td>
											<td>{{ $Challan->id }}</td>
											<?php $customer = App\Models\Customer::find($Challan->customer_id); ?>
											<td>{{ ($customer) ? $customer->customer_name : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->phone_no : 'N/A' }}</td>
											<td>{{ ($customer) ? $customer->company_name : 'N/A' }}</td>
											<td style="width:10%;">
												<a href="{{ route('DeliveryChallan.show', $Challan->id) }}" target="_blank">
													<i class="fa fa-eye" style="color: #000"></i>
												</a>
												<a href="{{ route('DeliveryChallan.edit', $Challan->id) }}" >
													<i class="fa fa-pencil-alt" style="color: #000"></i>
												</a>
												<form action="{{ route('DeliveryChallan.destroy', $Challan->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Challan?');" style="display: inline-block;background: transparent; border:none;">
							                   			<i class="fa fa-trash-alt"></i>
							                	</button>
								            	</form>
											</td>
											<td>
											   <a href="{{ route('duplicate-delivery-challan',$Challan->id) }}">Duplicate</a>
											</td>
											<td>
												<a href="{{ route('ChallanExport', $Challan->id) }}">Export</a>
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
