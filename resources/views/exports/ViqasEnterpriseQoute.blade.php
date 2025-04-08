<?php $customer = App\Models\Customer::find($Qoute->customer_id); ?>
<table>
	<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
    <tr>
    	<th colspan="2">REF: VE-Q-{{ $Qoute->id }}-{{ date('Y') }}</th>
        <td colspan="3">{{ date("d-m-Y", strtotime($Qoute->dated)) }}</td>
    </tr>
    <tr>
        <th colspan="5">To,</th>
    </tr>
    
    <tr>
        <td colspan="5"><strong>{{ $customer->customer_name }}</strong></td>
    </tr> 
    
    <tr>
        <td colspan="5"><strong>{{ $customer->city }}</strong></td>
    </tr>

    <tr></tr>
    <tr></tr>

    <tr>
    	<td colspan="5">SUBJECT: <strong><u>{{$Qoute->subject}}</u></strong></td>
    </tr>
    <tr>
		<td colspan="4">Dear Sir,</td>
    </tr>
    <tr>
		<td colspan="5">Our best rates as following:</td>
    </tr>


	<tr></tr><tr></tr>

    <tr>
    	<th>S.No</th>
    	<th>Description</th>
		<th>SIZE</th>
		<th>QTY</th>
		<th>UOM</th>
		<th>U.Price</th>
		<th>Amount</th>
    </tr>
    
		<?php $totalPrice = 0; $count=1; ?>
		@foreach($QouteProducts as $Product)
			<tr>
				<td style="text-align: left;">{{ $count }}</td>
				<?php $productData = App\Models\Product::find($Product->product_id); ?>
				<td>{{ $productData->name }}</td>
				
				<td style="text-align: left;">
					@if(isset($Product->productCapacity))
					{{ $Product->productCapacity }}
					@else
					1.00
					@endif
				</td>
				
				<td style="text-align: left;">{{ number_format($Product->qty, 2) }}</td>
				<td>{{ $productData->unit }}</td>
				<td style="text-align: left;">{{ number_format($Product->unit_price, 2) }}</td> 
				

				<?php 
				// Capacity
			    $price1 = $Product->unit_price;

			    $totalPrice += $Product->qty*$price1;
				?>
				<td style="text-align: left;">{{ number_format($Product->qty*$price1, 2) }}</td>
			</tr>
			<?php $count++; ?>
		@endforeach

		@if($Qoute->other_products_name)
			<?php 
			$moreProductsNames = explode('@&%$# ', $Qoute->other_products_name);
			$moreProductsQty   = explode('@&%$# ', $Qoute->other_products_qty);
			$moreProductsPrice = explode('@&%$# ', $Qoute->other_products_price);
			$moreProductsUnit = explode('@&%$# ', $Qoute->other_products_unit);
			$count2 = 0;
			?>
			@foreach($moreProductsNames as $moreP)
			<tr>
                <td style="text-align: left;">{{$count}}</td>
                <td>{{$moreP}}</td>
                <td style="text-align: left;">1.00</td>
                <?php $qty =$moreProductsQty[$count2]; $price = $moreProductsPrice[$count2];?>
                <td style="text-align: left;">{{ number_format($moreProductsQty[$count2],2) }}</td>
                <td style="text-align: left;">{{ $moreProductsUnit[$count2] }}</td>
                <td style="text-align: left;">{{ number_format($moreProductsPrice[$count2],2)  }}</td>
                <td style="text-align: left;">{{ number_format($qty*$price,2)  }}</td>
            </tr>
            <?php $totalPrice += $qty*$price; $count++; $count2++; ?>
			@endforeach
			@endif  
		<tr>
			<th colspan="6" style="text-align: right;">Subtotal</th>
			<td style="text-align: left;">{{ number_format($totalPrice, 2) }}/-</td>
		</tr>

		@if($Qoute->GST == 'on')
		<tr>
			<th colspan="6" style="text-align: right;">GST 17%</th>
			<?php $tax = 0.17*$totalPrice;?>
			<td style="text-align: left;">{{ number_format($tax, 2) }}/-</td>
		</tr>
		@else
			<?php $tax = 0;?>
		@endif

		@if($Qoute->transportaion != '')
			<?php $transportaion = $Qoute->transportaion;?>
			<tr>
				<th colspan="6" style="text-align: right;">Transportaion</th>
				<td style="text-align: left;">{{ number_format($transportaion, 2) }}/-</td>
			</tr>
		@else
			<?php $transportaion = 0;?>
		@endif

		<?php $netTotal = $totalPrice+$tax+$transportaion; ?>
		<tr>
			<th colspan="6" style="text-align: right;">Total Amount</th>
			<td style="text-align: left;">{{ number_format($netTotal, 2)}}/-</td>
		</tr>
		<tr></tr><tr></tr>

		<tr>
			<td colspan="7" rowspan="3">
				{!! $Qoute->termsConditions !!}
			</td>
		</tr>

		<tr></tr><tr></tr>
		<tr></tr><tr></tr>

		<tr>
			<td colspan="7">
				Best Regard,
			</td>
		</tr>
		<tr>
			<td colspan="7">
				VIQAS
			</td>
		</tr>
		<tr>
			<td colspan="7">
				Proprietor
			</td>
		</tr>
</table>