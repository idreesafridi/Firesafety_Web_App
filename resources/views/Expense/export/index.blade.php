 @extends('layouts.app')

@section('content')

<div class="fixed-navbar">
    <div class="float-left">
        <button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
        <h1 class="page-title">Select option to proceed</h1>
    </div>
</div>

<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
		    <div class="col-12">
				<div class="box-content">
					<h4 class="box-title">Select Range</h4>
					<div class="row">
						<div class="col-xl-12">
							<form action="{{ route('exoprtedexpense') }}" method="post" class="form-horizontal">
							@csrf
								<div class="form-group row">
									<div class="col-sm-3">
										<select name="branch" id="branch" class="form-control" style="height: 28px;">
                			                <option value="">Select Branch</option>
                			                @foreach($branches as $branch)
                			                    <option value="{{ $branch->branch_name }}">{{ $branch->branch_name }}</option>
                			                @endforeach
                			            </select>
									</div>
									<div class="col-sm-3">
										<input type="date" name="from" id="from" class="form-control" style="height: 40px!important;">
									</div>
									<div class="col-sm-3">
										<input type="date" name="to" id="to" class="form-control" style="height: 40px!important;">
									</div>
									<div class="col-sm-2">
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
		</div>
	</div>
</div>

@endsection
