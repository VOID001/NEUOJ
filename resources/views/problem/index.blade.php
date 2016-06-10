@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Problem {{$problem->problem_id}}</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script>
		$(function() {
			$("#problem").addClass("active");
			$("#promblem_submit_textarea").on('input focus', function() {
				if($("#promblem_submit_textarea").val().length<50 || $("#promblem_submit_textarea").val().length>50000) {
					$("#hint_code").addClass("label-warning");
				}else{
					$("#hint_code").removeClass("label-warning");
				}
			});
			$("#submit_code").click(function() {
				if($("#promblem_submit_textarea").val().length >= 50 && $("#promblem_submit_textarea").val().length<=50000) {
					$(this).attr("disabled",true);
					setTimeout("$('#submit_code').removeAttr('disabled');",3000);
					$("#form_code").submit();
				}
			});
		});
	</script>
</head>
<body>
	@include("layout.header")
	<h3 class="custom-heading">Problem: {{ $problem->title }}</h3>
	<div class="front-container">
	<div class="text-center text-primary front-time-box">
		Time limit: {{ $problem->time_limit }}s&nbsp;&nbsp;&nbsp;&nbsp;
		Mem limit:
		@if($problem->mem_limit < 1024)
			{{ $problem->mem_limit }} KB
		@else
			{{ $problem->mem_limit / 1024 }} MB
		@endif
		&nbsp;&nbsp;&nbsp;
		@if($problem->is_spj == 1)
			<b>Special Judge</b>&nbsp;&nbsp;&nbsp;
		@endif
		@if(isset($contest))
			AC/Submission: <a href="/contest/{{ $contest->contest_id }}/status/p/1?result=Accepted?pid={{ $problem->problem_id }}"/>{{ $problem->acSubmissionCount }}</a>/<a href="/contest/{{ $contest->contest_id }}/status/p/1?pid={{ $problem->problem_id }}">{{ $problem->totalSubmissionCount }}</a>
			&nbsp;&nbsp;&nbsp;<a href="/discuss/{{ $contest->contest_id }}/{{ $problem->problem_id }}"><b>Discuss</b></a>
		@else
			AC/Submission: <a href="/status/p/1?result=Accepted&pid={{ $problem->problem_id }}"/>{{ $problem->acSubmissionCount }}</a>/<a href="/status/p/1?pid={{ $problem->problem_id }}">{{ $problem->totalSubmissionCount }}</a>
			&nbsp;&nbsp;&nbsp;<a href="/discuss/0/{{ $problem->problem_id }}"><b>Discuss</b></a>
		@endif
		@if($roleCheck->is('admin'))
			@if(isset($contest))
				&nbsp;&nbsp;&nbsp;<a href="/dashboard/problem/{{ $problem->realProblemID }}"><b>Manage</b></a>
			@else
				&nbsp;&nbsp;&nbsp;<a href="/dashboard/problem/{{ $problem->problem_id }}"><b>Manage</b></a>
			@endif
		@endif
	</div>
	@if(isset($contest))
		<div class="front-time-box text-center">
			<a class="btn btn-default" href="/contest/{{ $contest->contest_id }}">&nbsp;&nbsp;Back&nbsp;&nbsp;</a>
			<a class="btn btn-default" href="/contest/{{ $contest->contest_id }}/ranklist">Ranklist</a>
			<a class="btn btn-default" href="/contest/{{ $contest->contest_id }}/status">&nbsp;Status&nbsp;</a>
			<span id="contest_countdown_text">Time Remaining:</span>
			<span class="label label-info">
				<b id="day_show">0天</b>
				<b id="hour_show">0时</b>
				<b id="minute_show">00分</b>
				<b id="second_show">00秒</b>
			</span>
		</div>
		<script type="text/javascript">
			var begin = new Date('{{$contest->begin_time}}').getTime();
			var now = new Date('{{date("Y-m-d H:i:s")}}').getTime();
			var end = new Date('{{$contest->end_time}}').getTime();
			var pretime = (begin - now) / 1000;
			var remaintime = (end - now) / 1000;
			var day = 0,
				hour = 0,
				minute = 0,
				second = 0;//时间默认值
			window.setInterval(function() {
				if(pretime <= 0) {
					$('#contest_countdown_text').html('Time Remaining:');
					showTime(remaintime);
				}
				if(pretime > 0) {
					$('#contest_countdown_text').html('Pending:');
					showTime(pretime);
				}
			}, 1000);
			function showTime(time) {
				if(time > 0) {
					day = Math.floor(time / (60 * 60 * 24));
					hour = Math.floor(time / (60 * 60)) - (day * 24);
					minute = Math.floor(time/ 60) - (day * 24 * 60) - (hour * 60);
					second = Math.floor(time) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
				}
				if (minute <= 9) minute = '0' + minute;
				if (second <= 9) second = '0' + second;
				$('#day_show').html(day + '天');
				$('#hour_show').html(hour + '时');
				$('#minute_show').html(minute + '分');
				$('#second_show').html(second + '秒');
				time--;
			}
		</script>
	@endif
	<div class="panel panel-default">
		<div class="panel-heading">Problem Description</div>
		<div class="panel-body">{!! $problem->description or "No Description!" !!}</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Input</div>
		<div class="panel-body">{!! $problem->input or "No Input!" !!}</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Output</div>
		<div class="panel-body">{!! $problem->output or "No Output!" !!}</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Sample Input</div>
		<div class="panel-body problem-index-panel-body">{{ $problem->sample_input or "No Sample Input!" }}</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Sample Output</div>
		<div class="panel-body problem-index-panel-body">{{ $problem->sample_output or "No Sample Output" }}</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Source</div>
		<div class="panel-body problem-index-panel-body">{{ $problem->source or "Source not avaliable!"}}</div>
	</div>
	<div class="panel panel-default">
		<div class="panel-heading">Hint</div>
		<div class="panel-body problem-index-panel-body">{{ $problem->hint or "No Hint!"}}</div>
	</div>
	@if(Request::session()->get('username') != NULL)
		<div class="text-center problem-index-btn"><a class="btn btn-info" data-toggle="modal" data-target=".modal">Submit</a></div>
	@else
		<div class="text-center problem-index-btn"><a href="/auth/signin">Sign in</a> to Submit your code</div>
	@endif
	<div class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header" id="problem-index-modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Submit</h4>
				</div>
				<div class="modal-body">
					@if(!isset($contest))
						<form id="form_code" action="/submit/{{ $problem->problem_id }}" method ="POST">
					@endif
					@if(isset($contest))
						<form id="form_code" action="/submit/{{ $contest->contest_id }}/{{ $problem->problem_id }}" method ="POST">
					@endif
					 {{ csrf_field() }}
						<span name="Language">language:</span>
						<select name="lang" class="form-control" id="problem-index-modal-select">
							<option name="c">C</option>
							<option name="cpp">C++</option>
						</select>
						<textarea class="form-control" id="promblem_submit_textarea" name="code" placeholder="Input your code here..."></textarea>
						<div id="problem-index-modal-footer">
							<input class="btn btn-primary pull-right" type="reset" value="&nbsp;Reset&nbsp;" />
							<input class="btn btn-primary pull-right" id="submit_code" type="button" value="Submit" />
							<div class="label" id="hint_code">the character range must be [50,50000]</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
	</div>
	@include("layout.footer")
</body>
</html>
