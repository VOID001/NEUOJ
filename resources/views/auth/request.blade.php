<!doctype html>
<html>
<head>
	<title>Forget Password</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script>
		$(function() {
			$('#captcha').click(function() {
				this.src = this.src + '?' + new Date();
			});
		});
	</script>
</head>
<body>
	@include("layout.header")
	<h3 class="custom-heading">Request</h3>
	<form action="/auth/request" method="POST">
		{{ csrf_field() }}
		<table class="custom-table auth-request-table">
			@if(isset($errors))
				@foreach($errors->all() as $error)
					<tr>
						<td colspan="3"><span class="label label-warning">{{ $error }}</span></td>
					</tr>
				@endforeach
			@endif
			@if(isset($info))
				<tr>
					<td colspan="3"><span class="label label-info">{{ $info }}</span></td>
				</tr>
			@endif
			<tr>
				<td>Email</td>
				<td><input class="form-control" name="email" type="text" /></td>
			</tr>
			<tr>
				<td>Captcha</td>
				<td><input class="form-control" name="captcha" type="text" /></td>
				<td><img id="captcha" src="{{ captcha_src("flat") }}" alt="Captcha" /></td>
			</tr>
			<tr>
				<td></td>
				<td class="text-right"><input type="submit" value="Reset"/></td>
			</tr>
		</table>
	</form>
	<p class="text-center">If you do not found email in 5 minutes, check for spam list</p>
</body>
</html>