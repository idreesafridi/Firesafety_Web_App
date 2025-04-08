@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Convert Quote</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('SaveConvertQuotation') }}" method="POST">
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
				<div class="box-content">
					<h4 class="box-title">Convert Quote</h4>
					<div class="form-group">
						<div class="row">
							<input type="hidden" name="id" value="{{ $id }}">
							<div class="form-group col-md-3">
								<input type="checkbox" id="invoice" name="invoice"> Invoice
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-3">
								<input type="checkbox" id="CashMemo" name="CashMemo"> Cash Memo
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-3">
								<input type="checkbox" id="DeliveryChallan" name="DeliveryChallan"> Delivery Challan
								<div class="help-block with-errors"></div>
							</div>

							<div class="form-group col-md-3">
								<input type="checkbox" id="IncomingChallan" name="IncomingChallan"> Incoming Challan
								<div class="help-block with-errors"></div>
							</div>
						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Convert Quote</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
</form>

<footer class="footer">
	<ul class="list-inline">
		<li>2020 © Al Akhzir Tech.</li>
		
	</ul>
</footer>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
@endsection
