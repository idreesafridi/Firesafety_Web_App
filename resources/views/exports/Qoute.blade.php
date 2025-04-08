<?php $customer = App\Models\Customer::find($Qoute->customer_id); ?>
<table>
	<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
    <tr>
    	<th></th>
        <th colspan="4" style="color: red; font-weight: bolder;font-size: 2em;text-align: center;">QUOTATION</th>
    </tr>
    <tr></tr>
    
    <tr>
        <th>M/S</th>
        <td colspan="3">{{ ($customer) ? $customer->company_name : 'N/A' }}</td>
        
        <td>Date</td>
        <td>{{ date("d/m/Y", strtotime($Qoute->dated)) }}</td>
    </tr>
    
    <tr>
        <td>Address</td>
        <td colspan="3">&nbsp;{{ ($customer) ? $customer->address : 'N/A' }}</td>
        
        <td>From</td>
        <td>{{ $user->username }}</td>
    </tr>
    
    <tr>
        <td>Email</td>
        <td colspan="3">{{ ($customer) ? $customer->email : 'N/A' }}</td>
        
        <td>Desig</td>
        <td>{{ $user->designation }}</td>
    </tr>
    
    <tr>
        <td>Ph No</td>
        <td colspan="3">{{ ($customer) ? $customer->phone_no : 'N/A' }}</td>
    	
        <td>Our NTN #</td>
        <td>&nbsp;0801321-7</td>
    </tr>
    
    <tr>
        <td>Att:</td>
        <td colspan="3">{{ ($customer) ? $customer->customer_name : 'N/A' }}</td>
        <td>Quote Ref:</td>
        <td>{{ $Qoute->id }}</td>
    </tr>

    <tr></tr>

    <tr>
    	<td colspan="5">{{$Qoute->subject}}</td>
    </tr>
    <!-- <tr>
    	<td colspan="4">
			    Sir, <br>
			    We have the pleasure in quoting our rates as under. We thank you for interest in our products and look forward for the opportunity to serve your demand.
    	</td>
    </tr> -->
    <tr>
		<td colspan="4">Sir, </td>
    </tr>
    <tr>
		<td colspan="4">We have the pleasure in quoting our rates as under. We thank you for interest</td>
    </tr>
    <tr>
		<td colspan="4">in our products and look forward for the opportunity to serve your demand.</td>
    </tr>


	<tr></tr><tr></tr>

    <tr>
    	<th>S.No</th>
    	<th>Product</th>
		<th>Size</th>
		<th>Quantity</th>
		<th>Unit Price</th>
		<th>Total</th>
    </tr>
    
		<?php $totalPrice = 0; $count=1; ?>
		@foreach($QouteProducts as $Product)
			<tr>
				<td style="text-align: left;">{{ $count }}</td>
				<?php $productData = App\Models\Product::find($Product->product_id); ?>
				<td>{{ $productData->name }}</td>
				
				<td style="text-align: left;">
					{{ $Product->productCapacity }}
				</td>
				
				<td style="text-align: left;">{{ number_format($Product->qty, 2) }}</td>
				<td style="text-align: left;">{{ number_format($Product->unit_price, 2) }}</td> 
				

				<?php 
				// Capacity
			 //   if(isset($Product->productCapacity)):
			 //   	$price1 = $Product->unit_price*$Product->productCapacity;
			 //   else:
			 //   	$price1 = $Product->unit_price;
			 //   endif;
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
			@if(!empty($moreP))
			<tr>
                <td style="text-align: left;">{{$count}}</td>
                <td>{{$moreP}}</td>
                <?php 
                $qty    =   ($moreProductsQty[$count2]) ? $moreProductsQty[$count2] : 0; 
                $price  =   ($moreProductsPrice[$count2]) ? $moreProductsPrice[$count2] : 0.00;
                ?>
                
                <td style="text-align: left;">{{ ($moreProductsQty[$count2]) ? number_format($moreProductsQty[$count2],2) : '0' }}</td>
                <td style="text-align: left;">{{ ($moreProductsQty[$count2]) ? number_format($moreProductsPrice[$count2],2) : '0'  }}</td>
                <td style="text-align: left;">1.00</td>
                <td style="text-align: left;">{{ number_format($qty*$price,2)  }}</td>
            </tr>
            <?php $totalPrice += $qty*$price; $count++; $count2++; ?>
            @endif
			@endforeach
			@endif   








		<tr>
			<th colspan="4" style="text-align: right;">Subtotal</th>
			<td style="text-align: left;">{{ number_format($totalPrice, 2) }}</td>
		</tr>

		@if($Qoute->GST == 'on')
		<tr>
			<th colspan="4" style="text-align: right;">GST 17%</th>
			<?php $tax = 0.17*$totalPrice;?>
			<td style="text-align: left;">{{ number_format($tax, 2) }}</td>
		</tr>
		@else
			<?php $tax = 0;?>
		@endif

		@if($Qoute->transportaion != '')
			<?php $transportaion = $Qoute->transportaion;?>
			<tr>
				<th colspan="4" style="text-align: right;">Transportaion</th>
				<td style="text-align: left;">{{ number_format($transportaion, 2) }}</td>
			</tr>
		@else
			<?php $transportaion = 0;?>
		@endif

		<?php $netTotal = $totalPrice+$tax+$transportaion; ?>
		<tr>
			<th colspan="4" style="text-align: right;">Total Amount</th>
			<td style="text-align: left;">{{ number_format($netTotal, 2)}}</td>
		</tr>

		<tr></tr>

		<tr>
			<td colspan="5" rowspan="5">
				{!! $Qoute->termsConditions !!}
			</td>
		</tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>
		<tr></tr>

		<tr>
			<td colspan="4">
				Thank you!
			</td>
		</tr>

		<tr></tr><tr></tr><tr></tr>
<!-- 		<tr>
			<td colspan="4">
                S. Khadim Hussain
                <br>
                |Managing Director| Universal Fire Protection Co (Pvt) Ltd| <br>
                |Tel:+9221-34530373,34381332| Fax:+9221-34530573|  <br>
                |Cell: +92315-4233313|  <br>
                |E-Mail:info@universalfireprotection.com.pk|
                </p>
			</td>
		</tr>
 -->
		<tr><td colspan="4">{{ $user->username }}</td></tr>
		<tr><td colspan="4">|Managing Director| Universal Fire Protection Co (Pvt) Ltd| </td></tr>
		<tr><td colspan="4">|Tel:+9221-34530373,34381332| Fax:+9221-34530573| </td></tr>
		<tr><td colspan="4">|Cell: +92315-4233313|</td></tr>
		<tr><td colspan="4"> |E-Mail:info@universalfireprotection.com.pk|</td></tr>
</table>