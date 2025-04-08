@extends('layouts.app')

@section('content')

<div class="fixed-navbar">
	<div class="float-left">
		<button type="button" class="menu-mobile-button fas fa-bars js__menu_mobile"></button>
		<h1 class="page-title">Change Password</h1>
		<!-- /.page-title -->
	</div>
	<!-- /.float-left -->

	<!-- /.float-right -->
</div>
<!-- /.fixed-navbar -->

<!-- /#message-popup -->

<!-- #color-switcher -->

<div id="wrapper">
	<div class="main-content">
		<div class="row small-spacing">
			<div class="col-12">
				<div class="box-content">
				<h4 class="box-title">Change Password</h4>
					
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
                    <form class="form-horizontal  form-bordered" method="POST" action="{{ route('updatePassword') }}" class="j-forms">
                    {{ csrf_field() }}   
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Old Password</label>
                                    <input id="current-password" type="password" class="form-control" name="current-password" required>

                                    @if ($errors->has('current-password'))
                                        <span class="help-block" style="color: red">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                
                                <div class="col-md-4">
                                    <label>New Password</label>
                                    <input id="new-password" type="password" class="form-control" name="new-password" required>

                                    @if ($errors->has('new-password'))
                                        <span class="help-block" style="color: red">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                                
                                <div class="col-md-4">
                                    <label>Confirm password</label>
                                    <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                            <button class="btn btn-success" type="submit">Update Password</button>
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
</div><!--/#wrapper -->

@endsection
