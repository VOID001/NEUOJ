<!doctype html>
<html>
<head>
	<title>Sign up</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
</head>
<body>
	@include("layout.header")
	<h3 class="text-center custom-heading">Sign up</h3>
	<form action="/auth/signup" method="POST">
		{{ csrf_field() }}
		@if(count($errors) > 0)
			<div class="text-center"><div class="label label-warning sign-warning">The username has already been taken.</div></div>
		@endif
		<table class="custom-table">
			<tr>
				<td>Username</td>
				<td><input class="form-control" type="text" name="username"/></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input class="form-control" type="password" name="pass"/></td>
			</tr>
			<tr>
				<td>Re-type Password</td>
				<td><input class="form-control" type="password" name="pass_confirmation"/></td>
			</tr>
			<tr>
				<td>Email Address</td>
				<td><input class="form-control" type="text" name="email"/></td>
			</tr>
			<tr>
				<td></td>
				<td class="text-right"><input class="btn btn-info" type="submit" value="Sign up"/></td>
			</tr>
		</table>
	</form>
	@include("layout.footer")
</body>
</html>