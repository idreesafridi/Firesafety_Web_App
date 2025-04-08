@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">All Expenses</h1>
    </div>
</div>


<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-12">
					<a href="{{ route('exoprtexpense') }}" class="btn btn-primary">
						<i class="fa fa-download"></i> Export
					</a>
				</div>
				
				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example" class="table table-striped table-bordered display" style="width:100%">
							<thead>
								<tr>
									<th>#</th>
									<th>Amount</th>
									<th>Branch</th>
									<th>Category</th>
									<th>Description</th>
									<th>Recipet</th>
									<th>Action</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>#</th>
									<th>Amount</th>
									<th>Branch</th>
									<th>Category</th>
									<th>Description</th>
									<th>Recipet</th>
									<th>Action</th>
								</tr>
							</tfoot>
							<tbody>
								@if($Expenses)
									<?php $count = 1; ?>
									@foreach($Expenses as $Expense)
										<tr>
											<td>{{ $count }}</td>
											<td>{{ $Expense->amount }}</td>
											<td>{{ ($Expense->branch) ? $Expense->branch->branch_name : 'N/A' }}</td>
											<td>{{ ($Expense->category) ? $Expense->category->name : 'N/A' }}</td>
											<td>{{ $Expense->description }}</td>
											<td><a href="/Expense/{{ $Expense->image }}" target="_blank">View</a></td>
											<td>
												<a href="{{ route('Expenses.edit',$Expense->id) }}" style="display: inline-block;color: #000">
													<i class="fa fa-pencil-alt"></i>
												</a>
												<form action="{{ route('Expenses.destroy', $Expense->id) }}" method="POST" style="display: inline-block;">
								                @csrf
								                @method('DELETE')
							                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this Expense?');" style="display: inline-block;background: transparent; border:none;">
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
