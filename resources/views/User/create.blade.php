@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Add User</h1>
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
				<h4 class="box-title">Add User</h4>
					<form data-toggle="validator" method="POST" action="{{ route('User.store') }}" enctype="multipart/form-data">
					@csrf()
						<div class="form-group">
							<div class="row">

								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="username" placeholder="User Name" name="username" value="{{ old('username') }}" required style="height: 40px">
									<div class="help-block with-errors"></div>
								 </div>

								<div class="form-group col-md-4">	
									<input type="text" class="form-control" id="address" placeholder="Address" name="address" value="{{ old('address') }}"  style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>

								<!--<div class="form-group col-md-4">	-->
								<!--	<input type="number" step="0.01" class="form-control" id="salary" placeholder="Salary" name="salary" value="{{ old('salary') }}">-->
								<!--	<div class="help-block with-errors"></div>-->
								<!--</div>-->


								<div class="form-group col-md-4">
									<select class="form-control select2_1" id="branch" name="branch" required  style="height: 40px">
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
									<select class="form-control select2_1" name="designation" id="designation" required  style="height: 40px">
										<option value="">Select Designation</option>
										<option value="Branch Admin" <?php echo (old('designation') == "Branch Admin") ? 'selected': ''; ?>>Branch Admin</option>
										<option value="Super Admin" <?php echo (old('designation') == "Super Admin") ? 'selected': ''; ?>>Super Admin</option>
										<option value="Staff" <?php echo (old('designation') == "Staff") ? 'selected': ''; ?>>Staff</option>
									</select>
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="custom_designation" placeholder="Custom Designation" name="custom_designation" value="{{ old('custom_designation') }}" style="height: 40px">
								</div>
								
								<div class="form-group col-md-4">
									<input type="number" class="form-control" id="phone_number" placeholder="Phone Number" name="phone_number" value="{{ old('phone_number') }}" style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email') }}" required style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>
							     <div class="form-group col-md-4">
									<input type="password" class="form-control" id="password" placeholder="Password" name="password" required style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<input type="password" class="form-control" id="password_confirmation" placeholder="Confirm Password" name="password_confirmation" required style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>

								
							</div>

							<div class="row">
								<div class="form-group col-md-4">
									<label>Signature</label>
									<input type="file" class="form-control" id="signature" placeholder="Signature" name="signature" required style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>
							</div>

								<div class="form-group col-md-12">
								    <hr>
								    <label>Rights</label>
								    <br>
								    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Branch">Branch
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="User">User
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Employee">Employee
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Supplier">Supplier
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Category">Category
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Product">Products
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Customer">Customers
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Quotes">Quotes
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Expense">Expenses
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Payroll">Payroll
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Reports">Reports
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Invoice">Invoice
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Accounts">Accounts
                                      </label>
                                    </div>
                                    
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Challan">Challans
                                      </label>
                                    </div>
                                    
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="CashMemo">Cash Memo
                                      </label>
                                    </div>
                                    
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="GeneralTemplate">General Templates
                                      </label>
                                    </div>

                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="TermsCondition">Terms & Condition
                                      </label>
                                    </div>

                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="BackupDatabase">Backup Database
                                      </label>
                                    </div>
                                </div> 
								</div>
								

								<!--<div class="form-group col-md-4">	-->
								<!--	<input type="text" class="form-control" id="bank" placeholder="Bank" name="bank" value="{{ old('bank') }}">-->
								<!--	<div class="help-block with-errors"></div>-->
								<!--</div>-->

								<!--<div class="form-group col-md-4">	-->
								<!--	<input type="text" class="form-control" id="account_no" placeholder="Account No" name="account_no" value="{{ old('account_no') }}">-->
								<!--	<div class="help-block with-errors"></div>-->
								<!--</div>-->
							    <div class="form-group col-md-4"></div>
					
								<div class="col-sm-3">
									<div class="input-group">
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Add</button>
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

@endsection
