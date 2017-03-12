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
		<h3 class="custom-heading" id="dashboard-custom-heading">Bind User</h3>
		<form action="/dashboard/settings/bind" method="POST">
			{{ csrf_field() }}
			<div class="text-center">
				@if(count($errors) > 0)
					@foreach($errors->all() as $error)
					<div class="label label-warning">{{ $error }}</div>
					@endforeach
				@endif
			</div>
			<table class="custom-table">
				<tr>
					<td>Your account</td>
                    @if(old('user_account') != NULL)
                    <td><input class="form-control" name="user_account" value="{{ old('user_account') }}" readonly/></td>
                    @else
                    <td><input class="form-control" name="user_account" value="{{ $user_account }}" readonly/></td>
                    @endif
				</tr>
                <tr>
                    <td>Your password</td>
					<td><input class="form-control" name="user_password" type="password" /></td>
                </tr>
				<tr>
					<td>Bind account</td>
                    @if(old('bind_account') != NULL))
                    <td><input class="form-control" name="bind_account" value="{{ old('bind_account') }}" /></td>
                    @else
                    <td><input class="form-control" name="bind_account" value="{{ $bind_account }}" /></td>
                    @endif
				</tr>
				<tr>
					<td>Bind account password</td>
					<td><input class="form-control" name="bind_password"type="password" /></td>
				</tr>
				<tr>
					<td></td>
					<td class="text-right"><input type="submit" value="Bind" class="btn btn-success" /></td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>