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
 		<form action="/dashboard/profile" method="POST" enctype="multipart/form-data">
 			{{ csrf_field() }}
 			<div class="text-center">
 				@if(count($errors) > 0)
 					<div class="label label-warning">{{$errors->all()[0]}}</div>
 				@elseif(isset($profileError))
 					<div class="label label-warning">{{ $profileError }}</div>
 				@endif
 			</div>
 			<div class="profile-avater">
				<div class="profile-title"></div>
				<img class="profile-img" id="dashboard-profile-img" src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif">
				<table class="profile-table">
				<tbody>
					<tr>
						<td>Avatar</td>
						<td><input name="image" accept="image/*" type="file"></td>
					</tr>
				</tbody>
				</table>
				<p class="profile-note">Note:Please make sure your image file is less than 1M!</p>
			</div>
			<div class="profile-info">
				<section class="boder-right">
				</section>
				<section class="boder-left">
					<section>			
						<table class="custom-table">
						<tbody>
							<tr>
								<td>真实姓名(NEU必填)</td>
								<td><input class="form-control" name="realname" value="@if(isset($realname)){{ $realname }}@endif" placeholder="This will be shown only to admin" type="text"></td>
							</tr>
							<tr>
								<td>Nickname</td>
								<td><input class="form-control" name="nickname" value="@if(isset($nickname)){{ $nickname }}@endif" type="text"></td>
							</tr>
							<tr>
								<td>School</td>
								<td><input class="form-control" name="school" value="@if(isset($school)){{ $school }}@endif" type="text"></td>
							</tr>
							<tr>
								<td>学号</td>
								<td><input class="form-control" name="stu_id" value="@if(isset($stu_id)){{ $stu_id }}@endif" type="text"></td>
							</tr>
						</tbody>
						</table>
						<input class="btn profile-button" value="Save" type="submit">
					</section>
				</section>
				{{$acCount}}
			</div>
		</form>
	</div>
</body>
</html>