<!doctype html>
<html>
<head>
	<title>Sign up</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
</head>
<body>
	@include("layout.header")
	<h3 class="custom-heading">Sign up</h3>
	<form action="/auth/signup" class="custom-table" method="POST">
		{{ csrf_field() }}
		<table>
			<tr>
				<td>Username</td>
				<td><input class="form-control" name="username" type="text" /></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input class="form-control" name="pass" type="password" /></td>
			</tr>
			<tr>
				<td>Re-type Password</td>
				<td><input class="form-control" name="pass_confirmation" type="password" /></td>
			</tr>
			<tr>
				<td>Email Address</td>
				<td><input class="form-control" name="email" type="text" /></td>
			</tr>
			<tr>
				<td></td>
				<td class="text-right"><input class="btn btn-info" type="submit" value="Sign up"/></td>
			</tr>
			<div>
				@if(count($errors) > 0 )
					@foreach($errors->all() as $error)
						<span class="label label-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{$error}}</span><br/>
					@endforeach
				@endif
			</div>
		</table>
	</form>
	@include("layout.footer")
</body>
</html>