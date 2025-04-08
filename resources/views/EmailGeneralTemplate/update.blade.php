@extends('layouts.app')

@section('content')


<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Add Template</h1>
	</div>
</div>
<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<div class="col-12">

				@if ($errors->any())
	                <div class="alert alert-danger">
	                    <ul>
	                        @foreach ($errors->all() as $error)
	                            <li>{{ $error }}</li>
	                        @endforeach
	                    </ul>
	                </div>
	            @endif

				<div class="box-content">
				<h4 class="box-title">Update Template</h4>
					<form data-toggle="validator" action="{{ route('EmailGeneralTemplate.update', $template->id) }}"  enctype="multipart/form-data" method="POST">
					@csrf()
					@method('PATCH')
						<div class="form-group">
							<div class="row">		
								<div class="form-group col-md-12">
									<input type="text" class="form-control" id="name" name="name" value="{{ $template->name }}" placeholder="Template Name" required>
									<div class="help-block with-errors"></div>
								</div>

								<div class="form-group col-md-12">
									<textarea class="form-control" id="tinymce" name="template" placeholder="Template">{{ $template->template }}</textarea>
									<div class="help-block with-errors"></div>
								</div>
								<div class="col-sm-3">
									<div class="input-group">
										<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Update Template</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>	
		<footer class="footer">
			<ul class="list-inline">
				<li>©2020 Al Akhzir Tech.</li>
			</ul>
		</footer>
	</div>
</div>
@endsection