@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Add Expense</h1>
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
				<h4 class="box-title">Add Expense</h4>
					<form data-toggle="validator" action="{{ route('Expenses.store') }}" method="POST" enctype="multipart/form-data">
					@csrf()
					<div class="container">
						<div class="form-group">
								<div class="row">
									<div class=" form-group col-md-4">
										<label for="branch_id">Branch: </label>
										<select class="form-control select2_1" id="branch_id" name="branch_id" required>
											<option value="">Select</option>
											@foreach($branches as $branch)
												@if(Auth::user()->role != 'super_admin' && Auth::user()->branch_id == $branch->id)
													<option value="{{ $branch->id }}" selected>{{ $branch->branch_name }}</option>
												@elseif(Auth::user()->designation == 'Super Admin')
													<option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
												@endif
											@endforeach
										</select>
										
										
									</div>
									<div class="form-group col-md-4">
										<input type="date" class="form-control" id="dated" name="dated" value="{{ date('Y-m-d') }}" style="height: 30px!important;">
										@if (session('error'))
										<div class="alert alert-danger alert-dismissible fade show" role="alert">
											<strong>Error:</strong> {{ session('error') }}
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
									@endif


									</div>
								</div>
							
								<div class="row mt-5">
									<table class="table" id="expensetable">
										<tbody>
											<tr>
												<td style="width:5%;">
													<input type="number" class="form-control sequence-number" name="sequence[]" value="1" style="height: 30px!important;" readonly>
												</td>
												<td style="width:25%" class="productTD">
													<select class="form-control select2_1 category" name="category_id[]" style="height: 30px!important;width:100%" required>
														<option value="">Category</option>
														@foreach($categories as $category)
														<option value="{{$category->id}}">{{$category->category}}</option>
														@endforeach
													</select>
												</td>
												<td style="width:40%">
													<input type="text" class="form-control qty" name="qty[]" placeholder="Detailed" style="height: 30px!important;">
													<div class="help-block with-errors"></div>
												</td>
												<td style="width:20%">
													<input type="number" step="0.01" class="form-control price" name="price[]" placeholder="Price" style="height: 30px!important;">
												</td>
												<td style="width:20%">
													<div class="input-group">
														<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
														&nbsp;
														<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
													</div>
												</td>
											</tr>
										</tbody>
									</table>
								</div>

							<div class="row mt-5">
								<div class="form-group col-md-4">
									<label>Sub Total</label>
									<input type="number" step="0.01" class="form-control" id="amount" placeholder="Expense Amount" value="{{ old('amount') }}" name="amount" required style="height: 40px;">
								</div>
								<div class="form-group col-md-4">
									<label>Cash Received</label>
									<input type="number" step="0.01" class="form-control" id="cashreceived" placeholder="Cash Received" value="{{ old('cashreceived') }}" name="cashreceived" required style="height: 40px;">
								</div>
								<div class="form-group col-md-4">
									<label>Previous Balance</label>
									<input type="number" step="0.01" class="form-control" id="pbalance" placeholder="P-Balance" value="{{ old('pbalance') }}" name="pbalance" required style="height: 40px;">
								</div>
								</div>
								<div class="row mt-5">
								<div class="form-group col-md-6">
									<label>Current Cash in Hand</label>
									<input type="number" step="0.01" class="form-control" id="cashinhand" placeholder="Current Cash in Hand" value="{{ old('cashinhand') }}" name="cashinhand" required style="height: 40px;">
								</div>
								<div class="form-group col-md-6">
									<label>Remaining Balance</label>
									<input type="number" step="0.01" class="form-control" id="remainingbalance" placeholder="Remaining Balance" value="{{ old('remainingbalance') }}" name="remainingbalance" required style="height: 40px;">
								</div>
								</div>


							<div class="row mt-5">
								<div class="col-sm-3">
									<div class="input-group">
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Add Expense</button>
									</div>
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
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2_1').select2();

        // Function to update sequence numbers
        function updateSequenceNumbers() {
            $('#expensetable tbody tr').each(function(index) {
                $(this).find('.sequence-number').val(index + 1);
            });
        }

        // Update Sub Total
        function updateSubTotal() {
            var subtotal = 0;
            $('.price').each(function() {
                subtotal += parseFloat($(this).val()) || 0;
            });
            $('#amount').val(subtotal.toFixed(2));
        }

        // Add row
        $(document).on('click', '.addmore', function() {
            var $currentRow = $(this).closest('tr');
            var $clone = $currentRow.clone();
            $clone.find('input').not('.sequence-number').val('');
            $clone.find('select').val('').trigger('change');
            $clone.find('.select2-container').remove(); // Remove the existing select2 container
            $clone.insertAfter($currentRow);

            // Reinitialize Select2 for new rows
            $clone.find('.select2_1').select2();
            updateSequenceNumbers();
            updateSubTotal();
        });

        // Delete row
        $(document).on('click', '.btnDelete', function() {
            if ($('#expensetable tbody tr').length > 1) {
                $(this).closest('tr').remove();
                updateSequenceNumbers();
                updateSubTotal();
            } else {
                alert("There must be at least one row.");
            }
        });

        // Update Sub Total on price change
        $(document).on('input', '.price', function() {
            updateSubTotal();
        });

        // Initial sequence number update
        updateSequenceNumbers();
        updateSubTotal();
    });

	$(document).ready(function() {
        function calculate() {
            var cashReceived = parseFloat($('#cashreceived').val()) || 0;
            var pBalance = parseFloat($('#pbalance').val()) || 0;
            var subTotal = parseFloat($('#amount').val()) || 0;

            var cashInHand = cashReceived + pBalance;
            $('#cashinhand').val(cashInHand.toFixed(2));

            var remainingBalance = cashInHand - subTotal;
            $('#remainingbalance').val(remainingBalance.toFixed(2));
        }

        $('#cashreceived, #pbalance, #amount').on('input', function() {
            calculate();
        });

        calculate(); // Call the function initially
    });
</script>


@endsection
