<?php $customer = App\Models\Customer::find($CashMemo->customer_id); ?>
<table>

	<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>

    <tr>
        <th colspan="4" style="color: red; font-weight: bolder;font-size: 2em;text-align: center;">Cash Memo</th>
    </tr>
    <tr></tr>


    <div class="row">
        <div class="col-12">
            <p><strong>{{ ($customer) ? $customer->company_name : 'N/A' }}</strong></p>
            @if(isset($customer->address))
            <p><strong>{{ $customer->address }}</strong></p>
            @endif
        </div>
    </div>
 
    <div class="row">
        <div class="col-7">
            <p>
                Customer Order Date: 
                @if(isset($CashMemo->customer_order_date))
                {{ date("d M, Y", strtotime($CashMemo->customer_order_date)) }}
                @endif
                <br>
                Customer Order No: {{$CashMemo->customer_order_no }}
            </p>
        </div>
        <div class="col-5">
            <p>Date: {{ date("d M, Y", strtotime($CashMemo->created_date)) }} <br>
            Reference No: {{ $CashMemo->reference_no }} <br>
        </div>
    </div>

    <tr></tr>


    <tr>
        <td>Salesperson</td>
        <td>NTN No.</td>
        <td>Sales Tax No.</td>
    </tr>
    <tr>
        <td>Syed Abrar Hussain Shah</td>
        <td style="text-align: left;">0801321-7-2</td>
        <td>07-02-84-24-003-64</td>
    </tr>
    
    <tr></tr>

	<tr>
		<th>Qty</th>
		<th>Descriptions</th>
        <th>Size</th>
	    <th>Per Cylinder</th>
	    <th>Amount</th> 
	</tr>
	<?php $count=0; $count1=1; ?>
	@foreach($Descriptions as $Description)
	@if(!empty($Description))
	<?php 
	$qty       = (float)$Quantity[$count];
	$price     = (float)$UnitPrice[$count];
    // $capacity  = (float)$productCapacity[$count];

    // $sub = $price*$capacity; 
    $sub = $price;
    
    $amount = $qty*$sub;
	?>
		<tr>
            <td style="text-align: left;">{{ $Quantity[$count] }}</td>
			<td style="text-align: left;">{{ $Description }}</td>
            <td style="padding:2px; text-align: right;">{{ $productCapacity[$count] }}</td>
			<td style="text-align: left;">{{ (float)$UnitPrice[$count] }}</td>
            <td style="text-align: left;">{{ $amount }}</td>
		</tr>
		<?php $count++;$count1++; ?>
	@endif
	@endforeach

	<tr></tr><tr></tr><tr></tr><tr></tr>

	<tr>
		<td>Direct All Inquiries To:</td>
		<td></td>
	</tr>
	<tr>
		<td>Syed Khadim Hussain</td>
		<td></td>
	</tr>

	<tr>
		<td><p>0332-5284828</p></td>
		<td><p>______________</p></td>
	</tr>
	<tr>
		<td><p>universalfireprotection@gmail.com</p></td>
		<td><p>Received By: (Name &#38; Stamp)</p></td>
	</tr>
</table>