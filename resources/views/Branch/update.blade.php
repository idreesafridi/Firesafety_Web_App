@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Update Branch</h1>
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
				<h4 class="box-title">Add Branch</h4>
					<form data-toggle="validator" action="{{ route('Branch.update', $Branch->id) }}" method="POST">
					@csrf()
					@method('PATCH')
						<div class="form-group">
							<div class="row">
								<div class="form-group col-md-6">
									<input type="text" class="form-control" id="branch_name" placeholder="Branch Name" name="branch_name" required value="{{ $Branch->branch_name }}">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-6">
									<input type="text" class="form-control" id="branch_address" placeholder="Address" name="branch_address" required value="{{ $Branch->branch_address }}">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-6">
									<input type="text" class="form-control" id="manager_name" placeholder="Manager Name" name="manager_name" required value="{{ $Branch->manager_name }}">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-6">
									<input type="number" class="form-control" id="phone_number" placeholder="Phone Number" name="phone_number" required value="{{ $Branch->phone_number }}">
									<div class="help-block with-errors"></div>
								</div>
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
