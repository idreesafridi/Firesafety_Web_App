@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Add Employee</h1>
    </div>
</div>

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
				<h4 class="box-title">Add Employee</h4>
					<form data-toggle="validator" action="{{ route('Employees.store') }}" method="POST">
					@csrf()
						<div class="form-group">
							<div class="row">
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="name" placeholder="Employee Name" value="{{ old('name') }}" name="name" required>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="phone" placeholder="Employee Phone" value="{{ old('phone') }}" name="phone" <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" />
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="email" placeholder="Employee Email" value="{{ old('email') }}" name="email">
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="type" placeholder="Employee Position" value="{{ old('type') }}" name="type">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<select class="form-control select2_1" id="branch" name="branch" required>
										<option value="">Select Branch</option>
										@if($Branches)
										@foreach($Branches as $Branch)
										<option value="{{ $Branch->branch_name }}" <?php echo (old('branch') == $Branch->branch_name) ? 'selected': ''; ?>>{{$Branch->branch_name}}</option>
										@endforeach
										@endif
									</select>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="address" placeholder="Employee Address" value="{{ old('address') }}" name="address">
									<div class="help-block with-errors"></div>
								</div>
								
								
								<div class="form-group col-md-4">
									<input type="number" step="0.01" class="form-control" id="salary" placeholder="Employee Salary" value="{{ old('salary') }}" name="salary">
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="bank" placeholder="Employee Bank Name" value="{{ old('bank') }}" name="bank">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="account_no" placeholder="Employee Account No" value="{{ old('account_no') }}" name="account_no" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-3">
									<tr>
										<td>Bike Maintenance</td>
										<td>
										<input type="checkbox" name="enable_bike_maintenance" onclick="togglebikemField(this)">
										<input type="number" step="0.01" name="bike_maintenance" id="bikem_field" value="{{ old('bike_maintenance', 0) }}" class="form-control" style="display:none;">							
										</td>
									</tr>
								</div>
								<div class="form-group col-md-4">
								</div>
								<div class="form-group col-md-3">
								</div>
								
								<div class="col-sm-3">
									<div class="input-group">
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Add Employee</button>
									</div>
								</div>
							</div>
						</div>
					</form>
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
<script>
		function togglebikemField(checkbox) {
        var bikemField = document.getElementById("bikem_field");
        bikemField.style.display = checkbox.checked ? "block" : "none";
    }
	</script>
@endsection
