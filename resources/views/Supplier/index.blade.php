@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Suppliers</h1>
    </div>
</div>

<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<div class="col-12">
				<div class="box-content table-responsive">
					<table id="example" class="table table-striped table-bordered display" style="width:100%">
						<thead>
							<tr>
								<th>Supplier Name</th>
								<th>Address</th>
								<th>Phone</th>
								<th>Mobile</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Supplier Name</th>
								<th>Address</th>
								<th>Phone</th>
								<th>Mobile</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<tbody>
							@if($Suppliers)
								@foreach($Suppliers as $Supplier)
									<tr>
										<td>{{ $Supplier->name }}</td>
										<td>{{ $Supplier->address }}</td>
										<td>{{ $Supplier->phone1 }} <?php echo ($Supplier->phone2 != '') ? ' - ' : '' ?> {{ $Supplier->phone2 }}</td>
										<td>{{ $Supplier->mobile1 }} <?php echo ($Supplier->mobile2 != '') ? ' - ' : '' ?> {{ $Supplier->mobile2 }}</td>
										<td>
											<a href="{{ route('Supplier.edit',$Supplier->id) }}" style="display: inline-block;color: #000">
												<i class="fa fa-pencil-alt"></i>
											</a>
											<form action="{{ route('Supplier.destroy', $Supplier->id) }}" method="POST" style="display: inline-block;">
							                @csrf
							                @method('DELETE')
						                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Supplier?');" style="display: inline-block;background: transparent; border:none;">
						                   			<i class="fa fa-trash-alt"></i>
						                	</button>
							            	</form>
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>		
		<footer class="footer">
			<ul class="list-inline">
				<li>2020 © Al Akhzir Tech.</li>
			</ul>
		</footer>
	</div>
	<!-- /.main-content -->
</div>

@endsection
