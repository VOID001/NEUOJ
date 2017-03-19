<!doctype html>
<html>
<head>
	<title>Setting</title>
	@include("layout.head")
	@include("layout.dashboard_header")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#dashboard_settings").addClass("dashboard-subnav-active");
		})
	</script>
</head>
<body>
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h3 class="custom-heading" id="dashboard-custom-heading">Settings</h3>
		<form action="/dashboard/settings" method="POST">
			{{ csrf_field() }}
			<div class="text-center">
				@if(count($errors) > 0)
					@foreach($errors->all() as $error)
					<div class="label label-warning">{{$error}}</div>
					@endforeach
				@elseif(isset($settingError))
					<div class="label label-warning">{{ $settingError }}</div>
				@endif
			</div>
			<table class="custom-table">
				<tr>
					<td>Old Password</td>
					<td><input class="form-control" type="password" name="old_pass" value="" /></td>
				</tr>
				<tr>
					<td>New Password</td>
					<td><input type="password" name="pass" value="" class="form-control" /></td>
				</tr>
				<tr>
					<td>Re-type Password</td>
					<td><input type="password" name="pass_confirmation" value="" class="form-control" /></td>
				</tr>
				<tr>
					<td></td>
					<td class="text-right"><input type="submit" value="Save" class="btn btn-success" /></td>
				</tr>
			</table>
		</form>
		@if(!preg_match("/20[0-9]{6}/",Request::session()->get('username')))
		@if($bindSSO != 0)
		<h4 align='center'>Binduser: {{ $bindSSO }}</h4>
		@else
		<form action="/dashboard/settings/bind" method="POST">
			{{ csrf_field() }}
			<h3 align="center">Bind User</h3>
			<table class="custom-table">
				<tr>
					<td>Username</td>
					<td><input class="form-control" name="bind_account"/></td>
					<td><input type="submit" value="Bind" class="btn btn-success" /></td>
				</tr>
			</table>
		</form>
		@endif
		@endif
	</div>
</body>
</html>