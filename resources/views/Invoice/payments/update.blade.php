@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Update Recieved Invoice Payment</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('UpdateInvocePayment', $payment->id) }}" method="POST">
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
					<div class="form-group">
						<h3>Payments Summary</h3>
						
					    <table class="table table-striped table-bordered">
					        <tbody>
					            <tr>
					                <th>Total Amount:</th>
					                <td>Rs {{ number_format($totalAmount, 2) }}</td>
					                
					                <th>Ex. GST Value:</th>
					                <td>Rs {{ number_format(getTotalInvoiceExTax($invoice->id), 2) }}</td>
					            </tr>
					            
					            <tr>
					                <th>Tax Rate:</th>
					                <td>{{ $invoice->tax_rate }}%</td>
					                
					                <th>Tax Amount:</th>
					                <td>Rs {{ number_format(getInvoiceTaxAmount($invoice->id), 2) }}</td>
					            </tr>
					            
					            <tr>
					                <th>Inc. Tax Value:</th>
					                <td colspan="3">Rs {{ number_format(getTotalInvoiceSales($invoice->id), 2) }}</td>
					            </tr>
					            
					            <tr>
					                <th>Total Recieved:</th>
					                <td>Rs {{ number_format($invoice->payments->sum('amount_recieved'), 2) }}</td>
			
					                <th>Total Remaining Amount:</th>
					                <td>Rs {{ number_format($remaining_amount, 2) }}</td>
					            </tr>
					            
					            <tr>
					                <th>Payment Status:</th>
					                <td colspan="3">{{ $invoice->payment_status }}</td>
					            </tr>
					        </tbody>
					    </table>

						<hr>
						<h3>Receive Payments</h3>
                                <div class="row">
                                    <h2 style="display: none;" id="remaining_amount">Rs {{ number_format($totalAmount, 2) }}</h2>
                                    <div class="form-group col-md-4">
                                        <label>Amount Received</label>
                                        <input type="number" step="0.01" max="{{ $totalAmount }}" value="{{ $payment->amount_recieved }}" class="form-control" name="amount_recieved" id="amount_received" placeholder="Amount Received" oninput="calculateRemainingAmount()">
                                        </div>
                                        <div class="form-group col-md-4" id="remainingAmountField" style="display: none;">
                                        <label>Remaining Amount</label>
                                        <input type="number" step="0.01" max="" class="form-control" name="remaining_amount" id="remaining_amount_input" placeholder="Remaining Amount">
                                        </div>
                                        <div class="form-group col-md-4">
                                        <label>WH Tax</label>
                                        <input id="wh_tax" type="number" step="0.01" class="form-control" value="{{ $payment->wh_tax }}" name="wh_tax" placeholder="WH Tax">
                                        </div>
                                        </div>
                                        <script>
                                            function calculateRemainingAmount() {
                                            var totalAmount = parseFloat({{ $totalAmount }});
                                            var amountReceived = parseFloat(document.getElementById("amount_received").value);
                                            var remainingAmount = totalAmount - amountReceived;
                                            var whTax = remainingAmount.toFixed(2);
                                            document.getElementById("remaining_amount").innerHTML = 'Rs ' + whTax;
                                            document.getElementById("remaining_amount_input").value = whTax;

                                            var paymentMode = document.getElementById("payment_mode").value;
                                            var remainingAmountField = document.getElementById("remainingAmountField");

                                            if (paymentMode === "partialpayment") {
                                            remainingAmountField.style.display = "block"; // Show the field for "Partial Payment"
                                            document.getElementById("wh_tax").value = "0"; // Set WH Tax to 0
                                            } else {
                                            remainingAmountField.style.display = "none"; // Hide for other payment modes
                                            document.getElementById("wh_tax").value = whTax; // Set WH Tax to calculated value
                                            }
                                            }

                                            // Function to handle the change in payment mode
                                            document.getElementById("payment_mode").addEventListener("change", function() {
                                            var paymentMode = this.value;
                                            var remainingAmountField = document.getElementById("remainingAmountField");

                                            if (paymentMode === "partialpayment") {
                                            remainingAmountField.style.display = "block"; // Show the field for "Partial Payment"
                                            document.getElementById("wh_tax").value = "0"; // Set WH Tax to 0
                                            } else {
                                            remainingAmountField.style.display = "none"; // Hide for other payment modes
                                            calculateRemainingAmount(); // Recalculate remaining amount when payment mode changes
                                            }
                                            });

                                            // Initial check for payment mode on page load
                                            document.addEventListener("DOMContentLoaded", function() {
                                            var paymentMode = document.getElementById("payment_mode").value;
                                            var remainingAmountField = document.getElementById("remainingAmountField");

                                            if (paymentMode === "partialpayment") {
                                            remainingAmountField.style.display = "block"; // Show the field for "Partial Payment"
                                            document.getElementById("wh_tax").value = "0"; // Set WH Tax to 0
                                            } else {
                                            remainingAmountField.style.display = "none"; // Hide for other payment modes
                                            }
                                            calculateRemainingAmount(); // Calculate remaining amount on page load
                                            });
                                        </script>
                                        <div class="form-group col-md-4">
                                        <label>Sales Tax</label>
                                        <input type="number" step="0.01" class="form-control" value="{{ $payment->sales_tax }}" name="sales_tax" value="0" id="sales_tax" placeholder="Sales Tax">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="payment_mode">Payment Mode</label>
                                        <select class="form-control" name="payment_mode" id="payment_mode">
                                            <option value="Cash">Cash</option>
                                            <option value="Cheque">Cheque</option>
                                            <option value="OnlineTransfer">Online Transfer</option>
                                            <option value="partialpayment">Partial Payment</option>
                                        </select>
                                    </div>
                                </div>
                            </div>        					
					<div class="col-sm-3">
						<div class="input-group">
							<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Update</button>
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
		<li>{{ date('Y') }} © Al Akhzir Tech.</li>
	</ul>
</footer>
</div>
</div>

@endsection
