@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Choose Quote Template</h1>
    </div>
</div>

<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example" class="table table-striped table-bordered display" style="width:100%">
							<tbody>
								<tr>
									<td style="width: 50%!important">
										<a href="{{ route('TemplateOne', $id) }}">
										<img src="/Template/Template1.png" class="img-thumbnail" style="height: 300px; width: 100%;">
										</a>
									</td>

									<td style="width: 50%!important">
										<a href="{{ route('TemplateTwo', $id) }}">
										<img src="/Template/Template2.png" class="img-thumbnail" style="height: 300px; width: 100%;">
										</a>
									</td>
								</tr>
								</tr>
								<tr>
									<td style="width: 50%!important">
										<a href="{{ route('TemplateThree', $id) }}">
										<img src="/Template/Template3.png" class="img-thumbnail" style="height: 300px; width: 100%;">
										</a>
									</td>

									<td style="width: 50%!important">
										<a href="{{ route('TemplateFour', $id) }}">
										<img src="/Template/Template4.png" class="img-thumbnail" style="height: 300px; width: 100%;">
										</a>
									</td>
								</tr>
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
