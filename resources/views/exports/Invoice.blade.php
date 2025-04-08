<?php $InvoiceCustomer = App\Models\Customer::find($Invoice->customer_id); ?>
<table>
    <tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
    <tr>
        <th colspan="4" style="color: red; font-weight: bolder;font-size: 2em;text-align: center;">INVOICE</th>
    </tr>
    <tr></tr>
    
    <tr>
        <th>M/S</th>
        <td colspan="2">{{ ($InvoiceCustomer) ? $InvoiceCustomer->company_name : 'N/A' }}</td>
        
        <td>Invoice NO</td>
        <td colspan="2">&nbsp;{{ $Invoice->invoice_no }}</td>
    </tr>
    
    <tr>
        
        <td>Address</td>
        <td colspan="2">&nbsp;{{ ($InvoiceCustomer) ? $InvoiceCustomer->address : 'N/A' }}</td>
        
        <td>Date</td>
        <td colspan="2">{{ date("d/m/Y", strtotime($Invoice->dated)) }}</td>

    </tr>
    
    <tr>
        <td>Email</td>
        <td colspan="2">{{($InvoiceCustomer) ? $InvoiceCustomer->email : 'N/A' }}</td>
        
        <td>From:</td>
        <td colspan="2">{{ $user->username }}</td>
    </tr>
    
    <tr>
        <td>Customer NTN</td>
        <td colspan="2">&nbsp;{{ $Invoice->customer_ntn_no }}</td>
    	
    	<td>Desig</td>
        <td>{{ $user->designation }}</td>
    </tr>
    
    <tr>
        <td>Att:</td>
        <td colspan="2">{{ ($InvoiceCustomer) ? $InvoiceCustomer->customer_name : 'N/A' }}</td>
        
    	
    	<td>Our NTN #</td>
        <td colspan="2">0801321-7</td>
    </tr>
    
    <tr>
        <td>Customer PO #</td>
        <td colspan="2">{{ $Invoice->customer_po_no }}</td>
        
        <td>Our GST</td>
        <td colspan="2">07-02-84-24-003-64</td>
    </tr>
    <?php
    $quote_id           = $Invoice->quote_id;
    if(isset($quote_id)):
        $delievery_challan  = App\Models\Challan::where('reference_no', $quote_id)->first();
        if(isset($delievery_challan)):
            $deliveryChallanNo = $delievery_challan->id;
        else:
            $deliveryChallanNo = '';
        endif;
    else:
        $deliveryChallanNo = '';
    endif;
    ?>
    <tr>
        <td>Delivery Challan #</td>
        <td colspan="2" style="text-align:left">{{ $deliveryChallanNo }}</td>
        
        <td>Our Quote No.</td>
        <td colspan="2" style="text-align:left">{{ $Invoice->quote_id }}</td>
    </tr>
    
    <tr></tr>

    <tr>
        <th>S.No</th>
        <th>Description</th>
        <th>Size</th>
        <th>Qty</th>
        <th>Unit Price</th>
        <th>Total</th>
    </tr>

    @if($InvoiceProducts)
        <?php $totalPrice = 0; $count=1 ?>
        @foreach($InvoiceProducts as $Product)
            <tr>
                <td style="text-align: left;">{{$count}}</td>
                <?php $productData = App\Models\Product::find($Product->product_id); ?>
                <td>{!! $productData->name !!}</td>
                
                <td style="text-align: left;">
                    {{ $Product->productCapacity }}
                </td>
                
                
                <td style="text-align: left;">{{ $Product->qty }}</td>
                
                <td style="text-align: left;">{{ number_format($Product->unit_price,2) }}</td>
                
                <?php 
                $price1 = $Product->unit_price;
                $totalPrice += $Product->qty*$price1;
                ?>
                
                <td>{{ number_format($Product->qty*$price1,2) }}</td>
            </tr>
            <?php $count++ ?>
        @endforeach
    @endif
    
    @if($Invoice->other_products_name)
        <?php 
        $moreProductsNames  = explode('@&%$# ', $Invoice->other_products_name);
        $moreProductsQty    = explode('@&%$# ', $Invoice->other_products_qty);
        $moreProductsPrice  = explode('@&%$# ', $Invoice->other_products_price);
        $moreProductsUnit   = explode('@&%$# ', $Invoice->other_products_unit);
        $count2 = 0;
        ?>
        @foreach($moreProductsNames as $moreP)
        @if(!empty($moreP))
        <tr>
            <td style="text-align: left;">{{$count}}</td>
            <?php $qty =$moreProductsQty[$count2]; $price = $moreProductsPrice[$count2];?>
            <td style="text-align: left;">{{$moreP}}</td>
            
            <td style="text-align: left;">1.00</td>
            
            <td style="text-align: left;">{{ number_format($moreProductsQty[$count2],2) }}</td>
            
            <td style="text-align: left;">{{ number_format($moreProductsPrice[$count2],2)  }}</td>
            <td style="text-align: left;">
            {{ number_format($qty*$price,2)  }}
            </td>
        </tr>
        <?php $totalPrice += $qty*$price; $count++; $count2++; ?>
        @endif
        @endforeach
        @endif 
    
    
    <tr>
        <td colspan="4"></td>
        <th>Subtotal</th>
        <td>{{ number_format($totalPrice, 2) }}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <th>GST 17%</th>
        <?php $tax = 0.17*$totalPrice;?>
        <td>{{ number_format($tax, 2) }}</td>
    </tr>

    @if($Invoice->transportaion != 0)
    <tr>
        <td colspan="4"></td>
        <th>Transportation</th>
        <?php 
        $transportaion = $Invoice->transportaion;
        $netTotal = $totalPrice+$tax+$transportaion;
        ?>
        <td>{{ ($transportaion) ? number_format($transportaion, 2) : '0.00' }}</td>
    </tr>
    @else
    <?php $netTotal = $totalPrice+$tax; ?>
    @endif
    <tr>
        <td colspan="4"></td>
        <th>Total Amount</th>
        <td>{{ number_format($netTotal, 2) }}</td>
    </tr>
    
    <tr></tr>
    <tr></tr>
    <tr></tr>
 
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


    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>
    <tr></tr>

    <tr>
        <td colspan="4" style="text-align: center;"><strong>Make All Payable to <u>Universal Fire Protection Co Pvt Ltd</u></strong> </td>
    </tr>
    <tr>
        <td colspan="4" style="text-align: center;">Thank You For Your Business ...!</td>
    </tr>

</table>