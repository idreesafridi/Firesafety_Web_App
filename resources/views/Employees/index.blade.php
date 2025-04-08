@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Employees</h1>
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
									<th>#</th>
									<th>Name</th>
									<th>Phone</th>
									<th>Address</th>
									<th>Position</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Phone</th>
									<th>Address</th>
									<th>Position</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Employees)
									<?php $count = 1; ?>
									@foreach($Employees as $Employee)
										<tr>
											<td>{{ $count }}</td>
											<td>{{ $Employee->name }}</td>
											<td>{{ $Employee->phone }}</td>
											<td>{{ $Employee->address }}</td>
											<td>{{ $Employee->type }}</td>
											<td>
												<a href="{{ route('Employees.edit',$Employee->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Employees.destroy', $Employee->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Employee?');" style="display: inline-block;background: transparent; border:none;">
							                   			<i class="fa fa-trash-alt"></i>
							                	</button>
								            	</form>
											</td>
										</tr>
										<?php $count++; ?>
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
