@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Users</h1>
    </div>
</div>

<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<div class="col-12">
				<div class="box-content">
					<h4 class="box-title">Search by Name or Designation</h4>
					<div class="row">
						<div class="col-xl-12">
							<form action="{{ route('UserSearch') }}" method="GET" class="form-horizontal">
								<div class="form-group row">
									<div class="form-group col-md-4">
										<input type="text" class="form-control select2_1" id="name" name="name" placeholder="Name">
									</div>
									<div class="form-group col-md-4">
										<select class="form-control select2_1" name="designation" id="designation">
										<option value="">Select Designation</option>
										<option value="Branch Admin">Branch Admin</option>
										<option value="Super Admin">Super Admin</option>
										<option value="Staff">Staff</option>
									</select>
									</div>
									<div class="col-sm-3">
										<div class="input-group">
											<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Search</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12">
				<div class="box-content table-responsive">
					<table id="example" class="table table-striped table-bordered display" style="width:100%">
						<thead>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Designation</th>
								<th>Custom Designation</th>
								<th>Phone #</th>
								<th>Address</th>
								<th>Actions</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Name</th>
								<th>Email</th>
								<th>Designation</th>
								<th>Custom Designation</th>
								<th>Phone #</th>
								<th>Address</th>
								<th>Actions</th>
							</tr>
						</tfoot>
						<tbody>
							@if($Users)
							@foreach($Users as $User)
							<tr>
								<td>{{ $User->username }}</td>
								<td>{{ $User->email }}</td>
								<td>{{ $User->designation }}</td>
								<td>{{ $User->custom_designation }}</td>
								<td>{{ $User->phone_number }}</td>
								<td>{{ $User->address }}</td>
								<td>
									<a href="{{ route('User.edit',$User->id) }}" style="display: inline-block;color: #000">
											<i class="fa fa-pencil-alt"></i>
										</a>
										<form action="{{ route('User.destroy', $User->id) }}" method="POST" style="display: inline-block;">
						                @csrf
						                @method('DELETE')
					                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this User?');" style="display: inline-block;background: transparent; border:none;">
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
</div>

@endsection
