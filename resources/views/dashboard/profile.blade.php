<!doctype html>
<html>
<head>
	<title>Profile</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#dashboard_profile").addClass("dashboard-subnav-active");
		})
	</script>
</head>
<body>
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h3 class="custom-heading">Profile</h3>
		<img class="center-block" id="dashboard-profile-img" src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif" />
		<form action="/dashboard/profile" method="POST" enctype="multipart/form-data">
			{{ csrf_field() }}
			<div class="text-center">
				@if(count($errors) > 0)
					<div class="label label-warning">{{$errors->all()[0]}}</div>
				@elseif(isset($profileError))
					<div class="label label-warning">{{ $profileError }}</div>
				@endif
			</div>
			<table class="custom-table">
				<tr>
					<td>Avatar</td>
					<td><input name="image" type="file" accept="image/*"></td>
				</tr>
				<tr>
					<td>真实姓名(NEU必填)</td>
					<td><input class="form-control" name="realname" type="text" value="@if(isset($realname)){{ $realname }}@endif"placeholder="This will be shown only to admin" /></td>
				</tr>
				<tr>
					<td>Nickname</td>
					<td><input class="form-control" name="nickname" type="text" value="@if(isset($nickname)){{ $nickname }}@endif" /></td>
				</tr>
				<tr>
					<td>School</td>
					<td><input class="form-control" name="school" type="text" value="@if(isset($school)){{ $school }}@endif" /></td>
				</tr>
				<tr>
					<td>学号</td>
					<td><input class="form-control" name="stu_id" type="text" value="@if(isset($stu_id)){{ $stu_id }}@endif" /></td>
				</tr>
				<tr>
					<td></td>
					<td class="text-right"><input class="btn btn-success" type="submit" value="Save" /></td>
				</tr>
			</table>
		</form>
		<p class="text-center">Note: Please make sure your image file is less than 1M!</p>
	</div>
</body>
</html>