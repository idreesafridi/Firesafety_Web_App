{{-- <table class="table" id="customerTable">
                                <?php $count=0; $sequence=1; ?>
                                
                                @if($QouteProducts->count() > 0)
    							    @foreach($QouteProducts as $QProduct)
    							    	<tr>
    							    		<th colspan="6"><input type="text" name="heading[]" class="form-control" value="{{ $QProduct->heading }}"></th>
    							    	</tr>
    								    <tr>
    								        <td style="width:10%;border: none;">
        										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
        									</td>
    								        
        									<td style="width:20%;border: none;" class="productTD">
        										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;width:100%">
        											<option value="">Select Product</option>
        											@if($products)
        											@foreach($products as $product)
        											<option value="{{ $product->id }}" <?php echo ($product->id == $QProduct->product_id) ? 'selected' : ''; ?>>{{ $product->name }}</option>
        											@endforeach
        											@endif
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
        									<td style="width:20%;border: none;">
        										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" value="{{ $QProduct->qty }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									<?php
        									$productData = App\Models\Product::find($QProduct->product_id);
        									$capacities  = explode(', ', $productData->capacity);
        									?>
        									<td style="width:20%;border: none;" class="productCapacityTD">
        										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
        										@foreach($capacities as $capacity) 
        										    <option value="{{ $capacity }}" <?php echo ($capacity == $QProduct->productCapacity) ? 'selected' : ''; ?>>{{ $capacity }}</option>
        										@endforeach
        										</select>
        										<div class="help-block with-errors"></div>
        									</td>
                                            <td style="width:20%;border: none;">
        										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" value="{{ $QProduct->unit_price }}" style="height: 30px!important;">
        										<div class="help-block with-errors"></div>
        									</td>
        									
        									<td style="width:20%;border: none;">
        										<div class="input-group">
        											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
        											&nbsp;
        										    <a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
        										</div>
        									</td>
        								</tr>
										<tr  data-trid="{{ $sequence }}">
                                            <th colspan="6">
                                                <a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>
												<div class="txtdescription_wrapper">
												<textarea id="tmce_{{ $sequence }}" name="description[]" class="form-control description3 txtdescription " placeholder="Description"  style="height: 200px">
												{{ isset($QProduct->description) ? $QProduct->description : $productData->description }}
                                                </textarea>
												</div>
                                                
                                            </th>
                                        </tr>							<?php $count++; $sequence++; ?>
    								@endforeach
								@else
								<tr><th colspan="6"><input type="text" name="heading[]" class="form-control"></th></tr>
								<tr>
							        <td style="width:10%;">
										<input type="number" class="form-control" id="sequence" name="sequence[]" value="{{ $sequence }}" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;" class="productTD">
										<select class="form-control select2_1 productName product_id" id="product_id_1" name="product_id[]" style="height: 40px!important;width:100%">
											<option value="">Select Product</option>
											@if($products)
											@foreach($products as $product)
											<option value="{{ $product->id }}">{{ $product->name }}</option>
											@endforeach
											@endif
										</select>
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;">
										<input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									<td style="width:20%;" class="productCapacityTD">
										<select class="form-control select2_1 productCapacity" id="productCapacity_1" name="productCapacity[]" style="height: 30px!important;">
										</select>
										<div class="help-block with-errors"></div>
									</td>
                                    <td style="width:20%;">
										<input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;">
										<div class="help-block with-errors"></div>
									</td>
									
									<td style="width:10%;">
										<div class="input-group">
											<a class="btn btn-primary btn-xs waves-effect waves-light addmore" style="color: #fff"><i class="fa fa-plus"></i></a>
											&nbsp;
        									<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>
										</div>
									</td>
								</tr>
								<tr>
								<tr  data-trid="1">
									<th colspan="6">
										<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>
										<textarea id="tmce_1" name="description[]" class="form-control description " placeholder="Description" style="height: 50px"></textarea>
									</th>
								</tr>
								@endif
							</table> --}}





							<script>

$("#terms").on('change', function(){
	var terms = $('#terms').find(":selected").val();
	//tinyMCE.activeEditor.setContent(terms);
	tinyMCE.get('txtarea_terms').setContent(terms);
	
});


$( document ).ready(function() {
	
	tinyMCE.init({
        selector: '.txtdescription'
      });

	  //$('textarea').hide();

	$("#customerTable").on('click', '.btnDelete', function () {
	    $(this).closest('tr').prev('tr').remove();
	    $(this).closest('tr').next('tr').remove();
	    $(this).closest('tr').remove();
	});
	
	var i=<?php echo $sequence; ?>;
	$("#customerTable").on('click', '.addmore', function(){
		var data='<tr><th colspan="6"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';
		
	    data +='<td class="productTD"><select class="form-control select2_1 productName product_id"  id="product_id_'+i+'" required name="product_id[]" style="height: 40px!important;width:100%">';
	    data +='<option value="">Select Product</option>';
	    @if($products)
	    @foreach($products as $product);
	    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
	    @endforeach 
	    @endif 
	    data +='</select></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"  required></td>';

	    data +='<td class="productCapacityTD"><select class="form-control select2_1 productCapacity" id="productCapacity_'+i+'" name="productCapacity[]" style="height: 30px!important;"></select></td>';

        data +='<td><input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;"></td>';

	    data +='<td style="width: 20%;">';
	    data +='<a class="btn btn-primary btn-xs waves-effect waves-light addmore2" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data += '&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';
	    
	    data +='</tr>';

	    data +='<tr data-trid="'+i+'">'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>';
		data += '<div class="txtdescription_wrapper">';
		data +='<textarea id="tmce_'+i+'" name="description[]" class="form-control description txtdescription" placeholder="Description" style="height: 50px"></textarea>';
		data += '</div>';
		data +='</th>';
		data +='</tr>';
		
	    //$('table#customerTable').prepend(data);
	    $(this).parent().parent().parent().next('tr').after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
		tinyMCE.init({
        	selector: '.txtdescription'
      	});
	    i++;
	});


	// this is for run time added plus button
	$("#customerTable").on('click', '.addmore2', function(){
		var data='<tr><th colspan="6"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
		data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';
		
	    data +='<td class="productTD"><select class="form-control select2_1 productName product_id"  id="product_id_'+i+'" required name="product_id[]" style="height: 40px!important;width:100%">';
	    data +='<option value="">Select Product</option>';
	    @if($products)
	    @foreach($products as $product);
	    data += '<option value="{{ $product->id }}">{{ $product->name }}</option>';
	    @endforeach 
	    @endif 
	    data +='</select></td>';
	    data +='<td><input type="number" class="form-control" id="qty" name="qty[]" placeholder="Quantity" style="height: 30px!important;"  required></td>';

	    data +='<td class="productCapacityTD"><select class="form-control select2_1 productCapacity" id="productCapacity_'+i+'" name="productCapacity[]" style="height: 30px!important;"></select></td>';

        data +='<td><input type="number" step="0.01" class="form-control price" id="price" name="price[]" placeholder="Price" style="height: 30px!important;"></td>';

	    data +='<td style="width: 20%;">';
	    data +='<a class="btn btn-primary btn-xs waves-effect waves-light addmore2" style="color: #fff"><i class="fa fa-plus"></i></a>';
	    data +='&nbsp;';
	    data +='<a class="btnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';
	    data +='</td>';
	    data +='</tr>';

		data +='<tr data-trid="'+i+'">'; 
		data +='<th colspan="6">';
        data +='<a class="btn btn-xs btn-primary view_hide_new" style="color: #fff;">View/Hide Description</a>';
		data += '<div class="txtdescription_wrapper">';
		data +='<textarea id="tmce_'+i+'" name="description[]" class="form-control description txtdescription" placeholder="Description" style="height: 50px"></textarea>';
		data += '</div>';
		data +='</th>';
		data +='</tr>';
		
	    //$('table#customerTable').prepend(data);
	    $(this).parent().parent().next('tr').after(data);
	    $('.product_id').select2();
	    $('.productCapacity').select2();
		tinyMCE.init({
        	selector: '.txtdescription'
      	});
	    i++;
	});
});


$( document ).ready(function() {

	$("#productsTable").on('click', '.productBtnDelete', function () {
	    $(this).closest('tr').prev('tr').remove();
	    $(this).closest('tr').remove();
	});

	var i=<?php echo $sequence_2+1; ?>;
	// this is for default plus button
	$(".addmoreProduct").on('click',function(){
		var data='<tr><th colspan="7"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';

	    data +='<td><input type="text" class="form-control" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 30px!important;"  required></td>'; 
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;"  required></td>'; 

	    data +='<td>';

	    data += '<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct2" style="color: #fff"><i class="fa fa-plus"></i></a>&nbsp;';
	    data += '<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';							
	    data += '</td>';

	    data +='</tr>';
	    
	    //$('table#productsTable').prepend(data);
	    $(this).parent().parent().parent().after(data);
	    i++;
	});

	$("#productsTable").on('click',  '.addmoreProduct2', function(){
		var data='<tr><th colspan="7"><input type="text" name="other_products_heading[]" placeholder="Heading" class="form-control"></th></th></tr>';
	    data +="<tr>";
	    data +='<td style="width:10%;">';
		data +='<input type="number" class="form-control" id="sequence" name="sequence[]" value="'+i+'" style="height: 30px!important;">';
		data +='<div class="help-block with-errors"></div>';
		data +='</td>';

	    data +='<td><input type="text" class="form-control" name="productName[]" placeholder="Product Name" style="height: 40px!important;" required></td>';
	    data +='<td><input type="number" class="form-control" id="productqty" name="productQty[]" placeholder="Quantity" style="height: 40px!important;" required></td>';
	     data +='<td><input type="text" class="form-control" id="size" name="size[]" placeholder="Size" style="height: 30px!important;"  required></td>'; 
	    data +='<td><input type="number" step="0.01" class="form-control" id="productPric" name="productPric[]" placeholder="Price" style="height: 40px!important;" required></td>';
	    data +='<td><input type="text" class="form-control" id="unit" name="unit[]" placeholder="Unit" style="height: 30px!important;"  required></td>'; 

	    data +='<td>';

	    data += '<a class="btn btn-primary btn-xs waves-effect waves-light addmoreProduct2" style="color: #fff"><i class="fa fa-plus"></i></a>&nbsp;';
	    data += '<a class="productBtnDelete btn btn-primary btn-xs waves-effect waves-light" style="color: #fff"><i class="fa fa-minus" aria-hidden="true"></i></a>';							
	    data += '</td>';

	    data +='</tr>';
	    
	    //$('table#productsTable').prepend(data);
	    $(this).parent().parent().after(data);
	    i++;
	});

});
</script>
@endsection
