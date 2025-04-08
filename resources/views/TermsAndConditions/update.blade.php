@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Update Terms & Conditions</h1>
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
				<h4 class="box-title">Update Terms & Conditions</h4>
					<form data-toggle="validator" action="{{ route('TermsAndCondition.update', $TermsAndConditions->id) }}" method="POST">
					@csrf()
					@method('PATCH')
						<div class="form-group">

							<div class="row mb-4">
								<div class="form-group col-md-12">
									<label for="name">Name</label>
									<input type="text" name="name" id="name" placeholder="Name" value="{{ $TermsAndConditions->name }}" class="form-control" required>
								</div>
							</div>

							<div class="row mb-4">
								<div class="form-group col-md-12">
									<label for="name">Terms & Conditions</label>
									<textarea class="form-control" id="tinymce" name="termsAndConditions" required>{{ $TermsAndConditions->termsAndConditions }}</textarea>
									<div class="help-block with-errors"></div>
								</div>
							</div>

							<div class="row mb-4">
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
