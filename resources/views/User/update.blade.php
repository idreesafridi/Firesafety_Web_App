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
					<form data-toggle="validator" method="POST" action="{{ route('User.update', $User->id) }}" enctype="multipart/form-data">
					@csrf()
					@method('PATCH')
						<div class="form-group">
							<div class="row">
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="username" placeholder="User Name" name="username" value="{{ $User->username }}" required style="height: 40px">
									<div class="help-block with-errors"></div>
								 </div>
								<div class="form-group col-md-4">	
									<input type="text" class="form-control" id="address" placeholder="Address" name="address" value="{{ $User->address }}" style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>

								<!--<div class="form-group col-md-4">	-->
								<!--	<input type="number" step="0.01" class="form-control" id="salary" placeholder="Salary" name="salary" value="{{ $User->salary }}">-->
								<!--	<div class="help-block with-errors"></div>-->
								<!--</div>-->

								<div class="form-group col-md-4">
									<select class="form-control select2_1" id="branch" name="branch" required style="height: 40px">
										<option value="">Select Branch</option>
										@if($Branches)
										@foreach($Branches as $Branch)
										<option value="{{ $Branch->branch_name }}" <?php echo ($User->branch == $Branch->branch_name) ? 'selected': ''; ?>>{{$Branch->branch_name}}</option>
										@endforeach
										@endif
									</select>
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group col-md-4">
									<select class="form-control select2_1" name="designation" id="designation" required style="height: 40px">
										<option value="">Select Designation</option>
										<option value="Branch Admin" <?php echo ($User->designation == "Branch Admin") ? 'selected': ''; ?>>Branch Admin</option>
										<option value="Super Admin" <?php echo ($User->designation == "Super Admin") ? 'selected': ''; ?>>Super Admin</option>
										<option value="Staff" <?php echo ($User->designation == "Staff") ? 'selected': ''; ?>>Staff</option>
									</select>
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group col-md-4">
									<input type="text" class="form-control" id="custom_designation" placeholder="Custom Designation" name="custom_designation" value="{{ $User->custom_designation }}" required style="height: 40px">
								</div>
								
								<div class="form-group col-md-4">
									<input type="number" class="form-control" id="phone_number" placeholder="Phone Number" name="phone_number" value="{{ $User->phone_number }}" style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-4">
									<input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ $User->email }}" required style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>

								<div class="form-group col-md-4">
									<label>Signature</label>
									<input type="file" class="form-control" id="signature" placeholder="Signature" name="signature" style="height: 40px">
									<div class="help-block with-errors"></div>
								</div>

								<!--<div class="form-group col-md-4">	-->
								<!--	<input type="text" class="form-control" id="bank" placeholder="Bank" name="bank" value="{{ $User->bank }}">-->
								<!--	<div class="help-block with-errors"></div>-->
								<!--</div>-->

								<!--<div class="form-group col-md-4">	-->
								<!--	<input type="text" class="form-control" id="account_no" placeholder="Account No" name="account_no" value="{{ $User->account_no }}">-->
								<!--	<div class="help-block with-errors"></div>-->
								<!--</div>-->
								
								<?php 
								if($User->rights != ''):
								    $rights = explode(', ', $User->rights); 
								else:
								    $rights = [];
								endif;
								?>
								<div class="form-group col-md-12">
								    <hr>
								    <label>Rights</label>
								    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Branch" <?php echo (in_array('Branch', $rights)) ? 'checked' : '' ?>>Branch
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="User" <?php echo (in_array('User', $rights)) ? 'checked' : '' ?>>User
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Employee" <?php echo (in_array('Employee', $rights)) ? 'checked' : '' ?>>Employee
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Supplier" <?php echo (in_array('Supplier', $rights)) ? 'checked' : '' ?>>Supplier
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Category" <?php echo (in_array('Category', $rights)) ? 'checked' : '' ?>>Category
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Product" <?php echo (in_array('Product', $rights)) ? 'checked' : '' ?>>Products
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Customer" <?php echo (in_array('Customer', $rights)) ? 'checked' : '' ?>>Customers
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Quotes" <?php echo (in_array('Quotes', $rights)) ? 'checked' : '' ?>>Quotes
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Expense" <?php echo (in_array('Expense', $rights)) ? 'checked' : '' ?>>Expenses
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Payroll" <?php echo (in_array('Payroll', $rights)) ? 'checked' : '' ?>>Payroll
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Reports" <?php echo (in_array('Reports', $rights)) ? 'checked' : '' ?>>Reports
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Invoice" <?php echo (in_array('Invoice', $rights)) ? 'checked' : '' ?>>Invoice
                                      </label>
                                    </div>
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Accounts" <?php echo (in_array('Accounts', $rights)) ? 'checked' : '' ?>>Accounts
                                      </label>
                                    </div>
                                    
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="Challan" <?php echo (in_array('Challan', $rights)) ? 'checked' : '' ?>>Challans
                                      </label>
                                    </div>
                                    
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="CashMemo" <?php echo (in_array('CashMemo', $rights)) ? 'checked' : '' ?>>Cash Memo
                                      </label>
                                    </div>
                                    
                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="GeneralTemplate" <?php echo (in_array('GeneralTemplate', $rights)) ? 'checked' : '' ?>>General Templates
                                      </label>
                                    </div>

                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="TermsCondition" <?php echo (in_array('TermsCondition', $rights)) ? 'checked' : '' ?>>Terms & Condition
                                      </label>
                                    </div>

                                    <div class="form-check-inline">
                                      <label class="form-check-label">
                                        <input type="checkbox" class="form-check-input" name="rights[]" value="BackupDatabase" <?php echo (in_array('BackupDatabase', $rights)) ? 'checked' : '' ?>>Backup Database
                                      </label>
                                    </div>
                                </div> 
                                
                            </div>

						<!-- 	    <div class="form-group col-md-4"></div> -->
					
								<div class="col-sm-3">
									<div class="input-group">
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Update</button>
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
