<!doctype html>
<html>
<head>
	<title>Setting</title>
	@include("layout.head")
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
		<h3 class="custom-heading">Settings</h3>
		<form action="/dashboard/settings" method="POST">
			{{ csrf_field() }}
			<div class="text-center">
				@if(count($errors) > 0)
					<div class="label label-warning">{{$errors->all()[0]}}</div>
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
	</div>
</body>
</html>