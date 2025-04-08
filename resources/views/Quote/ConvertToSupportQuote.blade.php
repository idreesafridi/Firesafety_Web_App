@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Update Price</h1>
    </div>
</div>

<div id="wrapper">
		<div class="main-content">
			<div class="row small-spacing">
				<div class="col-12">
					<div class="box-content table-responsive">
						<table id="example" class="table table-striped table-bordered display" style="width:100%">
							<tbody>
								<form data-toggle="validator" action="{{ route('ConvertToSupportQuoteSave') }}" method="POST">
								@csrf
									<input type="hidden" name="id" value="{{ $id }}">
									<tr>
										<th>Viqas Enterprise</th>
										<td>
											<input type="number" class="form-control" id="priceViqasEnterprise" name="priceViqasEnterprise" placeholder="Increase %" style="height: 30px!important;" value="{{ old('priceViqasEnterprise') }}" required>
										</td>
									</tr>
									<tr>
										<th>Safety Care</th>
										<th>
											<input type="number" class="form-control" id="priceSafetyCare" name="priceSafetyCare" placeholder="Increase %" style="height: 30px!important;" value="{{ old('priceSafetyCare') }}" required>
										</th>
									</tr>
									<tr>
										<td colspan="2">
											<button type="submit" class="btn btn-primary btn-sm waves-effect waves-light">Convert</button>
										</td>
									</tr>
								</form>
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
