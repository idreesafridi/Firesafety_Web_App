@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Recieve Payments</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('Accounts.store') }}" method="POST">
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
					<h4 class="box-title">Recieve Payments</h4>
					<div class="form-group">
							<div class="row">
								<div class="form-group col-md-4">
									<select class="form-control" id="invoice_id" required name="invoice_id" style="height: 40px!important;">
										<option value="">Select Invoice</option>
										@if($Invoices)
										@foreach($Invoices as $Invoice)
										<?php $customer = App\Models\Customer::find($Invoice->customer_id);  ?>
										<option value="{{ $Invoice->id }}">Invoice No: {{ $Invoice->invoice_no  }}</option>
										@endforeach
										@endif
									</select>
								</div>
								<div class="form-group col-md-4">
									<input type="number" step="0.00" class="form-control" id="remaining" name="remaining" placeholder="Remaining Amount" readonly style="height: 40px!important;">
								</div>
								<div class="form-group col-md-4">
									<input type="number" step="0.00" class="form-control" id="amount_paid" name="amount_paid" placeholder="Amount Paid" style="height: 40px!important;" required>
								</div>
							</div>
							<div class="row">
								<div class="form-group col-md-12"> 
									<textarea name="comments" id="comments" class="form-control"></textarea>
								</div>
							</div>
						</div>
					</div>
				    
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Recieve Amount</button>
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

<script type="text/javascript">
$.ajaxSetup({
	headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});
 
$("#invoice_id").change(function(){
var invoice_id = $(this).val();

$.ajax({
   	type:'GET',
   	url:'/getAllAmount',
   	data:{invoice_id:invoice_id},
   	success:function(response){
        console.log(response);
        $("#remaining").val(response);
        $("#amount_paid").prop('max',response);
   	}
});
});
</script>
@endsection
