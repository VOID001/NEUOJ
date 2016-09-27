<!doctype html>
<html>
<head>
	<title>Welcome come to NEU online judge</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#home").addClass("active");
		})
	</script>
	<style>
		#content {
			margin: 0 auto;
			background-image: url(/image/homePage.jpeg);
			width: 960px;
			height: 400px;
			margin-bottom: 40px;
		}
		.home-text{
			color: #fff;
			font-size: 30px;
			font-family: Avant Garde;
			text-align: center;
			padding-top: 100px;
			padding-bottom: 50px;
		}
		.homesignin-text{
			color: #fff;
			font-size: 24px;
			font-family: sans-serif;
			text-align: center;
			font-weight:lighter;
			padding-top: 20px;
		}
		.homesignin-message-group{
			margin-left: 13%;
		}
		.homesignin-contest{
			float: left;
			margin-right: 30px;
		}
		.homesignin-contest-line{
			width: 300px;
			height: 3px;
			background-color: #67a8da;
		}
		.homesignin-contest-content{
			width: 300px;
			height: 400px;
			border: 1px solid #ccc;
			overflow: hidden;
		}
		.homesignin-training{
			float: left;
			margin-right: 30px;
		}
		.homesignin-training-line{
			width: 300px;
			height: 3px;
			background-color: #faaf43;
		}
		.homesignin-training-content{
			width: 300px;
			height: 400px;
			border: 1px solid #ccc;
			overflow: hidden;
		}
		.homesignin-problem{
			float: left;
		}
		.homesignin-problem-line{
			width: 300px;
			height: 3px;
			background-color: #f26767;
		}
		.homesignin-problem-content{
			width: 300px;
			height: 400px;
			border: 1px solid #ccc;
			overflow: hidden;
		}
		#contest_list li, #training_list li, #problem_list li{
			padding-left: 10px;
		}
		#contest_list li a, #training_list li a, #problem_list li a{
			font-family: Avant Garde;
			font-size: 15px;
			font-weight: 600;
			line-height: 8px;
			letter-spacing: 1px;
		}
	</style>
</head>
<body>
	@include("layout.header")
	@if(Request::session()->get('username') == '')
	<div id='content'>
		<p class="home-text" >Welcome come to NEU online judge</p>
		<div class='text-center'>
			<a href="/auth/ssologin"><paper-button class="sign-button" raised>SSO</paper-button></a>
		</div>
		<div style="padding-bottom: 20px;"></div>
		<div class='text-center'>
			<a href="/auth/signin"><paper-button class=" sign-button" raised>Sign in</paper-button></a>
		</div>
	</div>
	<div class="text-primary text-justify" id="homepage">
		欢迎使用 NEUOJ ver1.5, <a href="http://202.118.31.226:3322/Lbyang/NEUOJ"><img src="http://202.118.31.226:3322/api/badges/Lbyang/NEUOJ/status.svg" /></a>在使用中遇到任何问题, 请在 <a href="https://github.com/VOID001/NEUOJ-bug-report/issues/new">[Github Issues]</a>提出, 或者联系: zhangjianqiu13@gmail.com
	</div>
	@else
		<h2 class="homesignin-text text-center" style="color: #000;;">Welcome , Have a nice coding journey today !</h2>
		<div class='homesignin-message-group'>
			<div class='homesignin-contest'>
				<div class='homesignin-contest-line'></div>
				<div class='homesignin-contest-content'>
					<h4 class="text-center">Running Contests</h4>
					<hr>
					<ul id="contest_list" class="list-unstyled"></ul>
				</div>
			</div>
			<div class='homesignin-training'>
				<div class='homesignin-training-line'></div>
				<div class='homesignin-training-content'>
					<h4 class="text-center">Trainings</h4>
					<hr>
					<ul id="training_list" class="list-unstyled"></ul>
				</div>
			</div>
			<div class='homesignin-problem'>
				<div class='homesignin-problem-line'></div>
				<div class='homesignin-problem-content'>
					<h4 class="text-center">Unsolved Problems</h4>
					<hr>
					<ul id="problem_list" class="list-unstyled"></ul>
				</div>
			</div>
			<div style="clear: both"></div>
		</div>
		<script>
			$(function(){
				$.ajax({
					url: '/ajax/contests',
					type: 'GET',
					async: true,
					dataType: 'json',
					success: function(data) {
						var obj = eval(data).contests;
						var times = obj.length > 14 ? 14 : obj.length;
						for(var i=0;i<times;i++) {
							var contest = '<li>' +
									'<a class="custom-word" href="/contest/' + obj[i].contest_id + '">&nbsp;' + (i+1) + '. ' +  obj[i].contest_name+ '</a></li>';
							$("#contest_list").append(contest);
						}
						var contest = '<br/><li>' +
								'<a class="custom-word" href="/contest/p/1">&nbsp;See All ...</a></li>';
						$("#contest_list").append(contest);
					}
				})
				$.ajax({
					url: '/ajax/trainings',
					type: 'GET',
					async: true,
					dataType: 'json',
					success: function(data) {
						console.log(data);
						var obj = eval(data);
						var times = obj.length > 14 ? 14 : obj.length;
						for(var i=0;i<times;i++) {
							var training = '<li>' +
									'<a class="custom-word" href="/training/' + obj[i].train_id + '">&nbsp;'+ (i+1) + '. ' + obj[i].train_name+ '</a></li>';
							$("#training_list").append(training);
						}
						var training = '<br/><li>' +
								'<a class="custom-word" href="/training">&nbsp;See All ...</a></li>';
						$("#training_list").append(training);
					}
				})

				$.ajax({
					url: '/ajax/unfinished_problems',
					type: 'GET',
					async: true,
					dataType: 'json',
					success: function(data) {
						console.log(data);
						var obj = eval(data).unfinished_problems;
						var times = obj.length > 14 ? 14 : obj.length;
						for(var i=0;i<times;i++) {
							var problem = '<li>' +
									'<a class="custom-word" href="/problem/' + obj[i].pid + '">&nbsp;' + (i+1) + '. ' +  obj[i].title+ '</a></li>';
							$("#problem_list").append(problem);
						}
						var problem = '<br/><li>' +
								'<a class="custom-word" href="/problem/p/1">&nbsp;See All ...</a></li>';
						$("#problem_list").append(problem);
					}
				})

			})
		</script>
	@endif
	@include("layout.footer")
</body>
</html>