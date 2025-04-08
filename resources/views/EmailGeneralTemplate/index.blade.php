@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">All Templates</h1>
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
								<th>S.No</th>
							   	<th>Template Name</th>
								<th>Action</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>S.No</th>
							   	<th>Template Name</th>
								<th>Action</th>
							</tr>
						</tfoot>
						<tbody>
							@if($templates)
							<?php $count=1; ?>
							@foreach($templates as $template)
							<tr>
								<td>{{ $count }}</td>
								<td>{{ $template->name }}</td>
								<td>
									<a href="{{ route('EmailGeneralTemplate.show',$template->id) }}" target="_blank" style="display: inline-block;color: #000">
										<i class="fa fa-download"></i>
									</a>
									<a href="{{ route('EmailGeneralTemplate.edit',$template->id) }}" style="display: inline-block;color: #000">
										<i class="fa fa-pencil-alt"></i>
									</a>
									<form action="{{ route('EmailGeneralTemplate.destroy', $template->id) }}" method="POST" style="display: inline-block;">
					                @csrf
					                @method('DELETE')
				                	<button  type="submit" onclick="return confirm('Are you sure you want to delete this template?');" style="display: inline-block;background: transparent; border:none;">
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
</div>
@endsection