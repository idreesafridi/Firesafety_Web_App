@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Staff</h1>
    </div>
</div>

<form method="POST" action="{{ route('Salary.store') }}">
@csrf()

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

					<div class="box-content table-responsive">
						<table class="table table-border">
							<tr>
								<th colspan="2">SALARY SLIP</th>
							</tr>
							<tr>
								<th>Employee Name:</th>
								<td>{{ $staff->name }}</td>
							</tr>
							<tr>
								<th>Month</th>
								<td><input type="month" name="month" value="{{ old('month') }}" class="form-control" required></td>
							</tr>

							<tr>
								<th>Earnings</th>
								<th>Amount/PKR</th>
							</tr>

							<tr>
								<td>Salary</td>
								<td>{{ $staff->salary }}</td>
							</tr>
							<input type="hidden" name="user_id" value="{{ $staff->id }}">
							<input type="hidden" name="salary" value="{{$staff->salary}}">

							<tr>
								<td>Bike Maintenance</td>
								<td>{{ $staff->bike_maintenance }}</td>
							</tr>				
							<tr>
								<td>Over Time (Hours)</td>
								<td><input type="number" name="over_time" value="{{ old('over_time_days',0) }}" class="form-control" required></td>
							</tr>
							<tr>
							<tr>
								<td>Allow Leave</td>
								<td><input type="number" name="allow_leave" value="{{ old('',0) }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>Night Half</td>
								<td><input type="number" step="0.01" name="night_half" value="{{ old('night',0) }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>Night Full</td>
								<td><input type="number" step="0.01" name="night_full" value="{{ old('night',0) }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>DNS Allounce</td>
								<td>
									<input type="checkbox" name="enable_dns" onclick="toggleDNSField(this)">
									<input type="number" step="0.01" name="dns_allounce" id="dns_allowance_field" value="{{ old('dns_allowance', 0) }}" class="form-control" style="display:none;">
								</td>
							</tr>
							<tr>
							<td>Medical Allounce</td>
							<td>
									<input type="checkbox" name="enable_medical" onclick="togglemedicalField(this)">
									<input type="number" step="0.01" name="medical_allounce" id="medical_allowance_field" value="{{ old('medical_allowance', 0) }}" class="form-control" style="display:none;">
								</td>
							</tr>

							<tr>
							<td>House Rent</td>
							<td>
									<input type="checkbox" name="enable_hrent" onclick="togglehrentField(this)">
									<input type="number" step="0.01" name="house_rent" id="hrent_allowance_field" value="{{ old('house_rent', 0) }}" class="form-control" style="display:none;">
								</td>
							</tr>
						   <tr>
								<td>Convence</td>
								<td>
								<input type="checkbox" name="enable_convence" onclick="toggleconvenceField(this)">
								<input type="number" step="0.01" name="convence" id="convence_field" value="{{ old('convence', 0) }}" class="form-control" style="display:none;">							
								</td>
							</tr>
							<tr>
								<td>Ensurance</td>
								<td>
								<input type="checkbox" name="enable_ensurance" onclick="toggleensuranceField(this)">
								<input type="number" step="0.01" name="ensurance" id="ensurance_field" value="{{ old('ensurance', 0) }}" class="form-control" style="display:none;">							
								</td>
							</tr>
							<tr>
								<td>Provident</td>
								<td>
								<input type="checkbox" name="enable_provident" onclick="toggleprovidentField(this)">
								<input type="number" step="0.01" name="provident" id="provident_field" value="{{ old('provident', 0) }}" class="form-control" style="display:none;">							
								</td>
							</tr>
							<tr>
								<td>Professional</td>
								<td>
								<input type="checkbox" name="enable_professional" onclick="toggleprofessionalField(this)">
								<input type="number" step="0.01" name="professional" id="professional_field" value="{{ old('professional', 0) }}" class="form-control" style="display:none;">							
								</td>
							</tr>
							<tr>
								<td>Tax</td>
								<td>
								<input type="checkbox" name="enable_tax" onclick="toggletaxField(this)">
								<input type="number" step="0.01" name="tax" id="tax_field" value="{{ old('tax', 0) }}" class="form-control" style="display:none;">							
								</td>
							</tr>
							<tr>
								<th>Deduction</th>
								<th>Amount/PKR</th>
							</tr>
							<tr>
								<td>Advance</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ old('advance',0) }}" name="advance" required></td>
							</tr>
							<tr>
								<td>Absent (Days)</td>
								<td><input type="number" class="form-control" value="{{ old('absent_days',0) }}" name="absent_days" required></td>
							</tr>

							<tr>
								<td>Half Day</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ old('half_day',0) }}" name="half_day" required></td>
							</tr>
							<tr>
								<td>Prepared by</td>
								<td>
									<select class="form-control" name="prepared_by" required>
									    <option value="">Select</option>
										<option value="Waqas"{{ old('prepared_by') === 'Waqas' ? ' selected' : '' }}>Waqas</option>
										<option value="Abrar"{{ old('prepared_by') === 'Abrar' ? ' selected' : '' }}>Abrar</option>
										<option value="nulll"{{ old('prepared_by') === 'null' ? ' selected' : '' }}>Null</option>
									</select>
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<button class="btn btn-primary" type="submit">Pay Amount</button>
								</td>
							</tr>
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

</form>
<script>
    function toggleDNSField(checkbox) {
        var dnsField = document.getElementById("dns_allowance_field");
        dnsField.style.display = checkbox.checked ? "block" : "none";
    }

    function togglemedicalField(checkbox) {
        var medicalField = document.getElementById("medical_allowance_field");
        medicalField.style.display = checkbox.checked ? "block" : "none";
    }
	function togglehrentField(checkbox) {
        var hrentField = document.getElementById("hrent_allowance_field");
        hrentField.style.display = checkbox.checked ? "block" : "none";
    }
	function toggleconvenceField(checkbox) {
        var convenceField = document.getElementById("convence_field");
        convenceField.style.display = checkbox.checked ? "block" : "none";
    }
	function toggleensuranceField(checkbox) {
        var ensuranceField = document.getElementById("ensurance_field");
        ensuranceField.style.display = checkbox.checked ? "block" : "none";
    }
	function toggleprovidentField(checkbox) {
        var providentField = document.getElementById("provident_field");
        providentField.style.display = checkbox.checked ? "block" : "none";
    }
	function toggleprofessionalField(checkbox) {
        var professionalField = document.getElementById("professional_field");
        professionalField.style.display = checkbox.checked ? "block" : "none";
    }
	function toggletaxField(checkbox) {
        var taxField = document.getElementById("tax_field");
        taxField.style.display = checkbox.checked ? "block" : "none";
    }
</script>
@endsection
