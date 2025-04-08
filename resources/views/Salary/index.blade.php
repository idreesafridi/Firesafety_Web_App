@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">All Salaries</h1>
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
							    <th>Staff Name</th>
								<th>Month</th>
								<th>Download</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
							   	<th>Staff Name</th>
								<th>Month</th>
								<th>Download</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<tbody>
							@if($Salaries)
							@foreach($Salaries as $Salary)
							<tr>
								<?php $user = App\Models\Employee::find($Salary->user_id);  ?>
                                @if ($user)
                                    <td>{{ $user->name }}</td>
                                @else
                                    <td>User not found</td>
                                @endif
                                <td>{{ date('F Y', strtotime($Salary->month)) }}</td>
								<td>
								    <a href="{{ route('Salary.show',$Salary->id) }}" style="display: inline-block;color: #000" target="_blank">
										<i class="fa fa-download" aria-hidden="true"></i>
									</a>
								</td>
								<td>
									<a href="{{ route('Salary.show',$Salary->id) }}" style="display: inline-block;color: #000">
										<i class="fa fa-eye"></i>
									</a>
									<a href="{{ route('Salary.edit',$Salary->id) }}" style="display: inline-block;color: #000">
										<i class="fa fa-pencil-alt"></i>
									</a>
									<form action="{{ route('Salary.destroy', $Salary->id) }}" method="POST" style="display: inline-block;">
					                @csrf
					                @method('DELETE')
				                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Salary?');" style="display: inline-block;background: transparent; border:none;">
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