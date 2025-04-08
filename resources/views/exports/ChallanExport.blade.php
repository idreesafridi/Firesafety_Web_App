<?php $customer = App\Models\Customer::find($Challan->customer_id); ?>
<table>
	<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
    <tr>
        <th colspan="4" style="color: red; font-weight: bolder;font-size: 2em;text-align: center;">
        	@if($Challan->type == 'Incoming')
        	INCOMING CHALLAN
        	@else
        	DELIVERY CHALLAN
        	@endif
    	</th>
    </tr>
    <tr></tr>

    <tr>
    	<td>M/S</td>
    	<td colspan="3">
    	    {{ ($customer) ? $customer->company_name : 'N/A' }} 
    	 @if(isset($customer->address))
    	 <br>
    	 {{ $customer->address }}
    	 @endif
    	</td>
    </tr>

    <tr>
    	<td>Customer Order No.</td>
    	<td>
    	    @if($Challan->customer_order_no)
                {{$Challan->order_no}}
            @endif
    	 </td>

    	<td>Reference No.</td>
    	<td style="text-align: left;">{{ $Challan->reference_no }}</td>
    </tr>

    <tr>
    	<td>Customer Order Date</td>
    	<td>
    		@if($Challan->customer_order_date)
		    {{ date("d M, Y", strtotime($Challan->customer_order_date)) }}
		    @else
		    _______________________
		    @endif
    	</td>
    	<td>Dated</td>
    	<td>
    		@if($Challan->dated)
    		{{ date("d M, Y", strtotime($Challan->created_date)) }}
    		@else
		    _______________________________
		    @endif
    	</td>
    </tr>

    <tr></tr><tr></tr>

    <tr>
        <th>S.No</th>
        <th>Descriptions</th>
      	<th>Size</th>
      	<th>Quantity</th>
      	<th>UOM</th>
      	<th>Remarks</th>
    </tr>

    <?php 
    $Descriptions   = explode('@&%$# ', $Challan->descriptions);
    $Quantity     	=  explode('@&%$# ', $Challan->qty);
    $Remarks    	=  explode('@&%$# ', $Challan->remarks);
    $Unit     		=  explode('@&%$# ', $Challan->unit);
    $productCapacity 	=  explode('@&%$# ', $Challan->productCapacity);
    ?>
	@if(!empty($Descriptions))
	    <?php $count=0; $count1=1; ?>
	  	@foreach($Descriptions as $Description)
	  	@if(!empty($Description))
	  	    <?php
	  	    $remarks1 = strip_tags($Remarks[$count]); // Replaces all spaces with hyphens.

            $remarks = preg_replace('/[^A-Za-z0-9\-]/', ' ', $remarks1); 
	  	    ?>
	    	<tr>
	      		<td style="text-align: left;">{{ $count1 }}</td>
	      		<td>{{ $Description }}</td>
                <td style="text-align: center;">{{ (isset($productCapacity[$count])) ? $productCapacity[$count] : 'N/A' }}</td>
	      		<td style="text-align: left;">{{ (isset($Quantity[$count])) ? number_format($Quantity[$count], 2) : 'N/A' }}</td>
	      		<td>{{ ($Unit[$count]) ? $Unit[$count] : 'N/A' }}</td>
	      		<td>{!! $remarks !!}</td>
	    	</tr>
	    	<?php $count++;$count1++; ?>
	    @endif
	  	@endforeach
	@endif

	<tr></tr><tr></tr><tr></tr><tr></tr>

	<tr>
		<td colspan="2" style="text-align: center;">________________________________</td>
		<td colspan="2" style="text-align: center;">________________________________</td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;">Stores Incharge</td>
		<td colspan="2" style="text-align: center;">Receiver's Signature</td>
	</tr>

</table>