@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Recieve Invoice Payment</h1>
    </div>
</div>

<form data-toggle="validator" action="{{ route('recieveInvocePayment', $invoice->id) }}" method="POST">
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
                                    <th>Total Remaining Amount:</th>
                                    <td><span class="badge badge-danger" style="padding: 8px 15px;font-size: 12px;">Rs {{ number_format($remaining_amount, 2) }}</span></td>
                                </tr>
                                <tr>
                                <th>Ex. GST Value:</th>
                                    <td>Rs {{ number_format(getTotalInvoiceExTax($invoice->id), 2) }}</td>
                                    <th>Total Recieved:</th>
                                    <td>                                        
                                        <span class="badge badge-success" style="padding: 8px 15px;font-size: 12px;">Rs {{ number_format($total_amount_recieved, 2) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                <th>Tax Rate:</th>
                                    <td>{{ $invoice->tax_rate }}%</td>
                                    <td colspan="2">
                                    <strong>Recieved amount:</strong> Rs {{ number_format($amount_recieved, 2) }} <br>
                                    </td>
                                </tr>
                                <tr>
                                <th>Tax Amount:</th>
                                    <td>Rs {{ number_format(getInvoiceTaxAmount($invoice->id), 2) }}</td>
                                    <td colspan="2">
                                    <strong>W.H Tax Recieved:</strong> Rs {{ number_format($wh_tax_recieved, 2) }} <br>
                                    </td>
                                </tr>
                                <tr>
                                <th>Inc. Tax Value:</th>
                                    <td>Rs {{ number_format(getTotalInvoiceSales($invoice->id), 2) }}</td>
                                    <td colspan="2">
                                    <strong>Sales Tax Recieved:</strong> Rs {{ number_format($sales_tax_recieved, 2) }} <br>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

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
