@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Change Profile</h1>
	</div>
</div>

<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<div class="col-12">
				<div class="box-content">
				<h4 class="box-title">Change Profile</h4>
					
					@if ($errors->any())
					    <div class="alert alert-danger">
					        <ul>
					            @foreach ($errors->all() as $error)
					                <li>{{ $error }}</li>
					            @endforeach
					        </ul>
					    </div>
					@endif

					@if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif  
                    <form class="form-horizontal" method="POST" action="{{ route('changeProfile') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}    
                        <div class="form-group">
                        	<div class="row">
	                            <div class="col-md-3">
	                                <label>Name</label>
	                                <input id="username" type="text" class="form-control" name="username" value="{{ Auth::User()->username }}" required>

	                                @if ($errors->has('username'))
	                                    <span class="help-block" style="color: red">
	                                    <strong>{{ $errors->first('username') }}</strong>
	                                </span>
	                                @endif
	                            </div>
                            
	                            <div class="col-md-3">
	                                <label>Email</label>
	                               <input id="email" type="email" class="form-control" name="email" value="{{ Auth::User()->email }}" required>

	                                @if ($errors->has('email'))
	                                    <span class="help-block" style="color: red">
	                                    <strong>{{ $errors->first('email') }}</strong>
	                                </span>
	                                @endif
	                            </div>
	                            
	                            <div class="col-md-3">
	                                <label>Phone</label>
	                               <input id="phone_number" type="phone_number" class="form-control" name="phone_number" value="{{ Auth::User()->phone_number }}" required>

	                                @if ($errors->has('phone_number'))
	                                    <span class="help-block" style="color: red">
	                                    <strong>{{ $errors->first('phone_number') }}</strong>
	                                </span>
	                                @endif
	                            </div>
	                            
	                            <div class="col-md-3">
	                                <label>Tel</label>
	                               <input id="Tel" type="tel" class="form-control" name="Tel" value="{{ Auth::User()->Tel }}" required>

	                                @if ($errors->has('Tel'))
	                                    <span class="help-block" style="color: red">
	                                    <strong>{{ $errors->first('Tel') }}</strong>
	                                </span>
	                                @endif
	                            </div>
	                            
	                        </div>

	                        <div class="row">
	                        	<div class="col-md-12">
	                                <label>Address</label>
	                                <textarea required name="address" id="address" class="form-control">{{ Auth::User()->address }}</textarea>

	                                @if ($errors->has('username'))
	                                    <span class="help-block" style="color: red">
	                                    <strong>{{ $errors->first('username') }}</strong>
	                                </span>
	                                @endif
	                            </div>
	                        </div>

	                        <div class="row" style="margin-top: 2em;">
	                            <div class="col-md-4 form-group">
									<label>Avatar</label><br>
									<input id="avatar" type="file" name="avatar">

	                                @if ($errors->has('avatar'))
	                                    <span class="help-block" style="color: red">
	                                    <strong>{{ $errors->first('avatar') }}</strong>
	                                </span>
	                                @endif
								</div>

								<div class="col-md-4 form-group">
									<label>Signature</label><br>
									<input id="signature" type="file" name="signature">

	                                @if ($errors->has('signature'))
	                                    <span class="help-block" style="color: red">
	                                    <strong>{{ $errors->first('signature') }}</strong>
	                                </span>
	                                @endif
								</div>
	                        </div>

	                        
                        </div>
                        <div class="form-group">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                            <button class="btn btn-success" type="submit">Update Profile</button>
                            </div>
                        </div>
                    </form>

				</div>

				<!-- /.box-content -->
			</div>
			<!-- /.col-12 -->
		</div>
		<!-- /.row small-spacing -->		
		<footer class="footer">
			<ul class="list-inline">
				<li>2020 © Al Akhzir Tech.</li>
			</ul>
		</footer>
	</div>
	<!-- /.main-content -->
</div>

@endsection
