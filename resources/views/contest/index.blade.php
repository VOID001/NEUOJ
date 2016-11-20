@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Contest {{ $contest->contest_id }}</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<meta http-equiv="Refresh" content="20">
	<script type="text/javascript">
		$(function(){
			$("#contest").addClass("active");
		})
	</script>
	<script type="text/javascript">
		var begin = new Date("{{$contest->begin_time}}").getTime();
		var now = new Date("{{ date('Y-m-d H:i:s') }}").getTime();
		var end = new Date("{{$contest->end_time}}").getTime();
		var pretime = (begin - now) / 1000;
		var remaintime = (end - now) / 1000;
		var day = 0,
			hour = 0,
			minute = 0,
			second = 0;//时间默认值
		window.setInterval(function() {
			if(pretime <= 0) {
				$('#contest_countdown_text').html("Time Remaining:");
				showTime(remaintime);
				remaintime--;
			}
			if(pretime > 0) {
				$('#contest_countdown_text').html("Pending:");
				showTime(pretime);
				pretime--;
			}
		}, 1000);
		function showTime(time) {
			if(time >= 0) {
				day = Math.floor(time / (60 * 60 * 24));
				hour = Math.floor(time / (60 * 60)) - (day * 24);
				minute = Math.floor(time/ 60) - (day * 24 * 60) - (hour * 60);
				second = Math.floor(time) - (day * 24 * 60 * 60) - (hour * 60 * 60) - (minute * 60);
				if (minute <= 9) minute = '0' + minute;
				if (second <= 9) second = '0' + second;
				$('#day_show').html(day + "天");
				$('#hour_show').html('<s id="h"></s>' + hour + '时');
				$('#minute_show').html('<s></s>' + minute + '分');
				$('#second_show').html('<s></s>' + second + '秒');
			}
		}
	</script>
</head>
<body class="body-scroll-y">
@include("layout.header")
	<div class="front-container">
		<h3 class="custom-heading">
			{{ $contest->contest_name }}
			@if($contest->status=="Running")
				<span class="label  label-info context-index-label">{{ $contest->status }}</span>
			@elseif($contest->status=="Ended")
				<span class="label label-default context-index-label">{{ $contest->status }}</span>
			@else
			<span class="label label-default context-index-label">{{ $contest->status }}</span>
			@endif
		</h3>
		<div class="text-center front-time-box">
			<span id="contest_single_begintime">Begin Time: {{ $contest->begin_time }}</span>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<span id="contest_single_endtime">End Time: {{ $contest->end_time }}</span>
		</div>
		<div class="text-center front-time-box">
			<span id="contest_countdown_text">Time Remaining:</span>
			<span class="label  label-info">
				<strong id="day_show">0天</strong>
				<strong id="hour_show">0时</strong>
				<strong id="minute_show">00分</strong>
				<strong id="second_show">00秒</strong>
			</span>
		</div>
		<div class="text-center front-time-box">
			<a class="btn btn-default" href="/contest/{{ $contest->contest_id }}/status">Status</a>
			<a class="btn btn-default" href="/contest/{{ $contest->contest_id }}/ranklist">Ranklist</a>
			@if($roleCheck->is("admin")||$roleCheck->is("balloon")) <!-- tmp role check for balloon -->
				<a class="btn btn-default" href="/contest/{{ $contest->contest_id }}/balloon">Balloon</a>
			@endif
			<a class="btn btn-default" href="/discuss/{{$contest->contest_id}}">BBS</a>
		</div>
		<div>
			<!--
			We will add customize view of contest later
			<form action="{{ Request::server('REQUEST_URI') }}" method="get">
				<button name="desc" value="1">Sort the problem in AC Ratio Desc</button>
			</form>
			-->
		</div>
		<table class="table table-striped table-bordered custom-list">
			<thead class="front-green-thead">
				<th class="text-center" width="10%">Status</th>
				<th class="text-center" width="12%">Problem ID</th>
				<th class="text-center" width="13%">Short Name</th>
				<th width="22%">&nbsp;Problem Name</th>
				<th class="text-center"  width="20%">AC/Total(Ratio)</th>
				@if($roleCheck->is('admin'))
					<th class="text-center" width="10%">Rejudge</th>
				@endif
			</thead>
			@foreach($problems as $problem)
				<tr class="front-table-row">
				<!--@if($problem->realProblemName !== -1 && ($roleCheck->is("admin") || $contest->status != "Pending"))-->
				<!--@endif-->
					<td>
						@if($problem->thisUserFB)
							<span class="glyphicon glyphicon-flag" id="contest-index-green-gly"></span>
							<span class="glyphicon glyphicon-map-marker"></span>
						@elseif($problem->thisUserAc)
							<span class="glyphicon glyphicon-map-marker"></span>
						@endif
					 </td>
					<td>
						<paper-button><a href="/contest/{{ $contest->contest_id }}/problem/{{ $problem->contest_problem_id }}">
							{{ $problem->contest_problem_id }}
						</a></paper-button>
					</td>
					<td>
						<paper-button><a class="custom-word" href="/contest/{{ $contest->contest_id }}/problem/{{ $problem->contest_problem_id }}">
							@if(!$roleCheck->is("admin") && !($contest->isRunning() || $contest->isEnded()))
								&nbsp;(╯‵A′)╯︵┻━┻
							@else
								&nbsp;{{ $problem->problem_title }}
							@endif
						</a></paper-button>
					</td>
					<td>
						<paper-button><a class="text-left custom-word" href="/contest/{{ $contest->contest_id }}/problem/{{ $problem->contest_problem_id }}">
						@if(!$roleCheck->is("admin") && !($contest->isRunning() || $contest->isEnded()))
							&nbsp;(╯‵A′)╯︵┻━┻
						@else
							&nbsp;{{ $problem->realProblemName === -1 ? "[Error] Problem Deleted!" : $problem->realProblemName }}
						@endif
						</a></paper-button>
					</td>
					<td>
						@if($roleCheck->is("admin" ) || ($contest->isRunning() || $contest->isEnded()))
							<paper-button><a href="/contest/{{ $contest->contest_id }}/status/p/1username=&pid={{ $problem->contest_problem_id }}&lang=All&result=All">
						@endif
						@if($problem->totalSubmissionCount != 0)
							{{ $problem->acSubmissionCount }} / {{ $problem->totalSubmissionCount }}({{ intval($problem->acSubmissionCount/$problem->totalSubmissionCount * 100) }}%)
						@else
							0 / 0
						@endif
						@if($contest->isRunning() || $contest->isEnded())
							</paper-button></a>
						@endif
					</td>
					@if($roleCheck->is('admin'))
						<td>
							<form method="post" action="/rejudge/{{ $contest->contest_id }}/{{ $problem->contest_problem_id }}">
								{{ csrf_field() }}
								<input class="btn btn-danger" type="submit" value="Rejudge"/>
							</form>
						</td>
					@endif
				</tr>
			@endforeach
		</table>
	</div>
	@include("layout.footer")
</body>
</html>