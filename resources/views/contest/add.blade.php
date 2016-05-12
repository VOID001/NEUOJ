<!doctype html>
<html>
<head>
	<title>Add Contest</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/searchFunction.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#dashboard_contest").addClass("dashboard-subnav-active");
			if($('#public-radio')[0].checked) {
				$('#private-table').hide();
				$('#register-table').hide();
			}else if($('#private-radio')[0].checked) {
				$('#private-table').show();
				$('#register-table').hide();
			}else {
				$('#private-table').hide();
				$('#register-table').show();
			}
			$('#public-radio').click(function() {
				$('#private-table').slideUp();
				$('#register-table').slideUp();
			})
			$('#private-radio').click(function() {
				$('#private-table').slideDown();
				$('#register-table').slideUp();
			})
			$('#register-radio').click(function() {
				$('#private-table').slideUp();
				$('#register-table').slideDown();
			})
		})
	</script>
</head>
<body >
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h3 class="custom-heading">Add Contest</h3>
		<form class="back-problem-form" action="/dashboard/contest/add" method="post">
			{{ csrf_field() }}
			<table class="custom-table">
				@foreach($errors->all() as $error)
					<tr>
						<td colspan="2"><span class="label label-warning">{{ $error }}</span></td>
					</tr>
				@endforeach
				<tr>
					<td>Contest Name</td>
					<td><input class="form-control" name="contest_name" type="text" value="{{ old('contest_name') }}" required /></td>
				</tr>
				<tr>
					<td>Begin Time</td>
					<td><input class="form-control" name="begin_time" type="datetime-local" value="{{ old('begin_time') }}" required /></td>
				</tr>
				<tr>
					<td>End Time</td>
					<td><input class="form-control" name="end_time" type="datetime-local" value="{{ old('end_time') }}" required /></td>
				</tr>
				<tr>
					<td>Contest Type</td>
					<td>
						<input id="public-radio" name="contest_type" type="radio" value="public" checked />public
						<input id="private-radio" name="contest_type" type="radio" value="private" />private
						<input id="register-radio" name="contest_type" type="radio" value="register" />register
					</td>
				</tr>
			</table>
			<div class="contest-b-private-table" id="private-table">
				<table class="custom-table">
					<tr>
						<td>Import user list</td>
						<td><input name="user_list" type="file" /></td>
					</tr>
					<tr>
						<td>Input Allowed Username</td>
						<td><textarea class="form-control" name="user_list" placeholder="Input the user name , seperate each with comma"></textarea></td>
					</tr>
				</table>
			</div>
			<div id="register-table">
				<table class="custom-table">
					<tr>
						<td>Register Begin Time</td>
						<td><input class="form-control" type="datetime-local" name="register_begin_time" value="{{ old('register_begin_time') }}" /></td>
					</tr>
					<tr>
						<td>Register End Time</td>
						<td><input class="form-control" type="datetime-local" name="register_end_time" value="{{ old('register_end_time') }}" /></td>
					</tr>
				</table>
			</div>
			<div class="text-center training-b-add-chapter">
				<label>Select Problem</label>
				<a href="javascript:addProblem()">Add Problem</a>
			</div>
			<div class="back-problem-add-list"></div>
			<input class="center-block" type="submit" value="Submit" />
		</form>
	</div>
	<script type="text/javascript">
		var titleData = [];
		$.ajax({
			url: '/ajax/problem_title',
			type: 'GET',
			async: true,
			dataType: 'json',
			success: function(result) {
				titleData = result;
			}
		});
		var count = 0;
		function addProblem() {
			var problemItem = '<div id=p_' + count + '>' +
				'<span>Problem ID </span>' +
				'<div class="search-container">' +
				'<input class="form-control search-title problem-id contest-b-problem-input" type="text" name="problem_id[]" autocomplete="off" />' +
				'<div class="search-option hidden"></div>' +
				'</div>' +
				'<span> Problem Title </span>' +
				'<input class="form-control problem-title contest-b-problem-input" type="text" name="problem_name[]" autocomplete="off" />' +
				'<span>Color</span>' +
				'<input class="form-control" style="width:5%;padding:0;" type="color" name=problem_color[] />' +
				'<a href="javascript:delProblem(' + count + ')">Delete Problem</a>' +
				'</div>';
			$('.back-problem-add-list').append(problemItem);
			count++;
			bindSearchFunction(titleData);
		}
		function delProblem(divId) {
			$('#p_' + divId).remove();
		}
	</script>
</body>
</html>