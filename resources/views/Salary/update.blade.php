@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Staff</h1>
    </div>
</div>

<form method="POST" action="{{ route('Salary.update', $Salary->id) }}">
@csrf()
@method('PATCH')
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
								<td>{{ $staff->username }}</td>
							</tr>
							<tr>
								<th>Month</th>
								<td><input type="month" name="month" value="{{ $Salary->month }}" class="form-control" required></td>
							</tr>

							<tr>
								<th>Earnings</th>
								<th>Amount/PKR</th>
							</tr>

							<tr>
								<td>Salary</td>
								<td>{{ $Salary->salary }}</td>
							</tr>
							<input type="hidden" name="user_id" value="{{ $staff->id }}">
							<input type="hidden" name="salary" value="{{$staff->salary}}">

							<tr>
								<td>Over Time (Hours)</td>
								<td><input type="number" name="over_time" value="{{ $Salary->over_time }}" class="form-control" required></td>
							</tr>
							
							<tr>
								<td>Leave Allow</td>
								<td><input type="number" name="allow_leave" value="{{ $Salary->absent_amount }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>Night Half</td>
								<td><input type="number" step="0.01" name="night_half" value="{{ $Salary->night_half }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>Night full</td>
								<td><input type="number" step="0.01" name="night_full" value="{{ $Salary->night_full }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>DNS Allounce</td>
								<td><input type="number" step="0.01" name="dns_allounce" value="{{ $Salary->dns_allounce}}" class="form-control" required></td>
							</tr>
							<tr>
								<td>Medical ALlounce</td>
								<td><input type="number" step="0.01" name="medical_allounce" value="{{ $Salary->medical_allounce }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>House Rent</td>
								<td><input type="number" step="0.01" name="house_rent" value="{{ $Salary->house_rent}}" class="form-control" required></td>
							</tr>
							<tr>
								<td>Convence</td>
								<td><input type="number" step="0.01" name="convence" value="{{ $Salary->convence }}" class="form-control" required></td>
							</tr>
							<tr>
								<td>Ensurance</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ $Salary->ensurance }}" name="ensurance" required></td>
							</tr>
							<tr>
								<td>Provident</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ $Salary->provident }}" name="provident" required></td>
							</tr>
							<tr>
								<td>Professional</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ $Salary->professional }}" name="professional" required></td>
							</tr>
							<tr>
								<td>Tax</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ $Salary->tax }}" name="tax" required></td>
							</tr>
							<tr>
								<td>Bike Maintenance</td>
								<td><input type="number" step="0.01" name="bike_maintenance" value="{{ $Salary->bike_maintenance }}" class="form-control" required></td>
							</tr>
							<tr>
								<th>Deduction</th>
								<th>Amount/PKR</th>
							</tr>
							<tr>
								<td>Advance</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ $Salary->advance }}" name="advance" required></td>
							</tr>
							<tr>
								<td>Absent (Days)</td>
								<td><input type="number" class="form-control" value="{{ $Salary->absent_days }}" name="absent_days" required></td>
							</tr>
							<tr>
								<td>Half Day</td>
								<td><input type="number" step="0.01" class="form-control" value="{{ $Salary->half_day }}" name="half_day" required></td>
							</tr>
							<tr>
								<td>Prepared by</td>
								<td><input type="text" class="form-control" value="{{ $Salary->prepared_by }}" name="prepared_by" required></td>
							</tr>

							<tr>
								<td colspan="2">
									<button class="btn btn-primary" type="submit">Update Salary</button>
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
@endsection






























