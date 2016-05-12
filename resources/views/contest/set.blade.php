<!doctype html>
<html>
<head>
	<title>Edit Contest</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<link rel="stylesheet" href="/css/contest.css">
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
<body>
	@include("layout.dashboard_nav")
	@if(time()>strtotime($contest->end_time))
		<script> alert('the contest is out of date');parent.location.href='/dashboard/contest'; </script>
	@endif
	<div class="back-container">
		<h3 class="custom-heading">Set Contest</h3>
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
					<td><input class="form-control" name="contest_name" type="text" value="{{ $contest->contest_name }}" required /></td>
				</tr>
				<tr>
					<td>Begin Time</td>
					<td>
						@if(time()<strtotime($contest->begin_time))
							<input name="begin_time" type="datetime-local" value="{{ str_replace(" ", "T", $contest->begin_time) }}" required />
						@else
							{{$contest->begin_time}}
							<input class="form-control" name = "begin_time" type="hidden" value="{{ $contest->begin_time }}" />
						@endif
					</td>
				</tr>
				<tr>
					<td>End Time</td>
					<td><input class="form-control" name="end_time" type="datetime-local" value="{{ str_replace(" ", "T", $contest->end_time) }}" required/></td>
				</tr>
				<tr>
					<td>Contest Type</td>
					<td>
						<input id="public-radio" name="contest_type" type="radio" value="public"
							@if($contest->contest_type == 0)
								checked
							@endif
						/>public
						<input id="private-radio" name="contest_type" type="radio" value="private"
							@if($contest->contest_type == 1)
								checked
							@endif
						/>private
						<input id="register-radio" name="contest_type" type="radio" value="register"
							@if($contest->contest_type == 1)
								checked
							@endif
						/>register
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
						<td>
							<textarea class="form-control" name="user_list" placeholder="Input the user name , seperate each with comma">
							@if(isset($contestUser))
								@for($i = 0;$i < count($contestUser); $i++)
									{{$contestUser[$i]['username']}}
									@if($i!=count($contestUser)-1)
									,
									@endif
								@endfor
							@endif
							</textarea>
						</td>
					</tr>
				</table>
			</div>
			<div id="register-table">
				<table class="custom-table">
					<tr>
						<td>Register Begin Time</td>
						<td>
							<input class="form-control" name="register_begin_time" type="datetime-local" value="{{ str_replace(" ", "T", $contest->register_begin_time) }}"
								@if($contest->contest_type == 2)
									required
								@endif
							/>
						</td>
					</tr>
					<tr>
						<td>Register End Time</td>
						<td>
							<input class="form-control" name="register_end_time" type="datetime-local" value="{{ str_replace(" ", "T", $contest->register_end_time) }}"
								@if($contest->contest_type == 2)
									required
								@endif
							/>
						</td>
					</tr>
				</table>
			</div>
			<div class="text-center training-b-add-chapter">
				<label>Select Problem</label>
				<a href="javascript:addProblem()">Add Problem</a>
			</div>
			<div class="back-problem-add-list">
				@for($i = 0; $i < $problem_count; $i++)
					<div id="p_{{ $i }}">
						<span>Problem ID</span>
						<input class="form-control contest-b-problem-input" name="problem_id[]" type="text" value="{{ $contestProblem[$i]['problem_id'] }}" readonly="true" />
						<span>Problem Title</span>
						<input class="form-control contest-b-problem-input" name="problem_name[]" type="text" value="{{ $contestProblem[$i]['problem_title'] }}" readonly="true" />
						@if(time()<strtotime($contest->begin_time))
							<a href="javascript:delProblem({{ $i }})">Delete Problem</a>
						@endif
					</div>
				@endfor
			</div>
			<input class="center-block" type="submit" value="Submit" />
		</form>
	</div>
	<script language="javascript">
		var titleData = [];
		$.ajax({
			url: '/ajax/problem_title',
			type: 'GET',
			async: true,
			dataType: 'json',
			success: function(result) {
				bindSearchFunction(result);
				titleData = result;
			}
		});
		var count = {{$problem_count}};
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