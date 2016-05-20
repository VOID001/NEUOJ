<!doctype html>
<html>
<head>
	<title>Profile</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
</head>
<body>
	@include("layout.header")
	<h3 class="text-center">Profile</h3>
	<img id="dashboard-profile-img" class="center-block" src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif" />
	<table class="custom-table">
		<tr>
			<td>Username</td>
			<td><input class="form-control" type="text" name="nickname" value="@if(isset($nickname)){{ $username }}@endif" readonly="true" /></td>
		</tr>
			<tr>
				<td>Nickname</td>
				<td><input class="form-control" name="nickname" type="text" value="@if(isset($nickname)){{ $nickname }}@endif" readonly="true" /></td>
			</tr>
			<tr>
				<td>School</td>
				<td><input class="form-control" name="school" type="text" value="@if(isset($school)){{ $school }}@endif" readonly="true" /></td>
			</tr>
			<tr>
				<td>学号</td>
				<td><input class="form-control" name="stu_id" type="text" value="@if(isset($stu_id)){{ $stu_id }}@endif" readonly="true" /></td>
			</tr>
	</table>
	@include("layout.footer")
</body>
</html>
