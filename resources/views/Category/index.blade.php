@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Categories</h1>
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
								    <th>Sr #</th>
									<th>Category Name</th>
									<th>Show Expire Invoices</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
								    <th>Sr #</th>
									<th>Category Name</th>
									<th>Show Expire Invoices</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Categories)
									@foreach($Categories as $Category)
										<tr>
										    <td>{{ $loop->iteration }}</td>
											<td>{{ $Category->name }}</td>
											<td>
												<form action="{{ route('update_expire_invoice', $Category->id) }}" method="post">
												@csrf
													<select name="expire_invoice" id="expire_invoice" onchange="this.form.submit();">
													<option value="no" {{ ($Category->expire_invoice == "no") ? 'selected' : '' }}>No</option>
													<option value="yes" {{ ($Category->expire_invoice == "yes") ? 'selected' : '' }}>Yes</option>
													</select>
												</form>
											</td>
											<td>
												<a href="{{ route('Category.edit',$Category->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Category.destroy', $Category->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Category?');" style="display: inline-block;background: transparent; border:none;">
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
