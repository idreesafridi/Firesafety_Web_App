@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Branches</h1>
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
									<th>Branch Name</th>
									<th>Address</th>
									<th>Manager Name</th>
									<th>Phone Number</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Branch Name</th>
									<th>Address</th>
									<th>Manager Name</th>
									<th>Phone Number</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Branches)
									@foreach($Branches as $Branch)
										<tr>
											<td>{{ $Branch->branch_name }}</td>
											<td>{{ $Branch->branch_address }}</td>
											<td>{{ $Branch->manager_name }}</td>
											<td>{{ $Branch->phone_number }}</td>
											<td>
												<a href="{{ route('Branch.edit',$Branch->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Branch.destroy', $Branch->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Branch?');" style="display: inline-block;background: transparent; border:none;">
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
