@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Add Supplier</h1>
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
				<h4 class="box-title">Add Supplier</h4>
					<form data-toggle="validator" action="{{ route('Supplier.store') }}" method="POST">
					@csrf()
						<div class="form-group">
							<div class="row">
								<div class="form-group col-md-6">
									<input type="text" class="form-control" id="name" placeholder="Supplier Name" name="name" required>
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-6">
									<input type="text" class="form-control" id="address" placeholder="Address" name="address" required>
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group col-md-6">
									<input type="number" class="form-control" id="phone1" placeholder="Phone 1" name="phone1" required onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-6">
									<input type="number" class="form-control" id="phone2" placeholder="Phone 2" name="phone2" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
									<div class="help-block with-errors"></div>
								</div>
								
								<div class="form-group col-md-6">
									<input type="number" class="form-control" id="mobile1" placeholder="Mobile 1" name="mobile1" required onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
									<div class="help-block with-errors"></div>
								</div>
								<div class="form-group col-md-6">
									<input type="number" class="form-control" id="mobile2" placeholder="Mobile 2" name="mobile2" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
									<div class="help-block with-errors"></div>
								</div>
								
								
								
								<div class="form-group col-md-6"></div>
								<div class="col-sm-3">
									<div class="input-group">
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Add Supplier</button>
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
