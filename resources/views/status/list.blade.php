@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Status</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/searchFunction.js"></script>
	<script type="text/javascript">
		$(function(){
			$("#status").addClass("active");
			$.ajax({
				url: '/ajax/problem_title',
				type: 'GET',
				async: true,
				dataType: 'json',
				success: function(result) {
					bindSearchFunction(result);
				}
			});
		})
	</script>
</head>
<body>
	@include("layout.header")
	<h3 class="custom-heading">Status List</h3>
	<div class="front-big-container">
		@if(isset($contest))
			<div class="front-time-box">
				<a class="btn btn-info" href="/contest/{{ $contest->contest_id }}">&nbsp;&nbsp;Back&nbsp;&nbsp;</a>
				<a class="btn btn-info" href="/contest/{{ $contest->contest_id }}/ranklist">Ranklist</a>
				<span id="contest_countdown_text">Time Remaining:</span>
				<span class="label label-info">
					<strong id="day_show">0天</strong>
					<strong id="hour_show">0时</strong>
					<strong id="minute_show">00分</strong>
					<strong id="second_show">00秒</strong>
				</span>
			</div>
			<script type="text/javascript">
				var begin = new Date("{{$contest->begin_time}}").getTime();
				var now = new Date("{{ date('Y-m-d H:i:s') }}").getTime();
				var end = new Date("{{$contest->end_time}}").getTime();
				var wholetime = (end - begin) / 1000;
				var pretime = (begin - now) / 1000;
				var remaintime = (end - now) / 1000;
				var day = 0,
					hour = 0,
					minute = 0,
					second = 0;//时间默认值
				window.setInterval(function() {
					if(pretime <= 0) {
						$('#contest_countdown_text').html("Time Remaining:");
						showTime(remaintime--);
					}
					if(pretime > 0) {
						$('#contest_countdown_text').html("Pending:");
						showTime(pretime--);
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
						$('#day_show').html(day + "天");
						$('#hour_show').html('<s id="h"></s>' + hour + '时');
						$('#minute_show').html('<s></s>' + minute + '分');
						$('#second_show').html('<s></s>' + second + '秒');
					}
			</script>
		@endif
		@if(!isset($contest))
			<form action="/status/p/1" method="GET" class="form-inline status-list-form">
		@else
			<form action="/contest/{{ $contest->contest_id }}/status/p/1" method="GET" class="form-inline status-list-form">
		@endif
			<span>Username:</span>
			<input class="form-control" type="text" name="username" />
			<span>Problem ID:</span>
			<div class="search-container">
				<input class="form-control search-title problem-id" type="text" name="pid" autocomplete="off" />
				<div class="search-option hidden"></div>
			</div>
			<span>Language:</span>
			<select name="lang" class="form-control">
				<option name="all">All</option>
				<option name="c">C</option>
				<option name="java">Java</option>
				<option name="cpp">C++</option>
				<option name="cpp11">C++11</option>
			</select>
			<select name="result" class="form-control">
				<option name="all">All</option>
				<option name="wt">Pending</option>
				<option name="ac">Accepted</option>
				<option name="wa">Wrong Answer</option>
				<option name="re">Runtime Error</option>
				<option name="pe">Presentation Error</option>
				<option name="tle">Time Limit Exceed</option>
				<option name="mle">Memory Limit Exceed</option>
				<option name="ole">Output Limit Exceed</option>
				<option name="je">Judge Error</option>
				<option name="ce">Compile Error</option>
			</select>
			<input type="submit" value="Filter" class="btn btn-info form-control"/>
		</form>
		<table class="table table-striped table-bordered custom-list" id="statuslist">
			<thead>
				<th class="text-center" width="6%">Run ID</th>
				<th class="text-center" width="13%">Submit Time</th>
				<th class="text-center" width="6%">User ID</th>
				<th class="text-center" width="9%">Username</th>
				<th class="text-center" width="10%">Nickname</th>
				<th width="11%">Problem Title</th>
				<th class="text-center" width="16%">Result</th>
				<th class="text-center" width="5%">Lang</th>
				<th class="text-center" width="8%">Ex_mem</th>
				<th class="text-center" width="7%">Ex_time</th>
				@if($roleCheck->is('admin'))
					<th class="text-center" width="9%">
						Rejudge
					</th>
				@endif
			</thead>
			@if($submissions != NULL)
				@foreach($submissions as $submission)
					<?php $param['runid'] = $submission->runid; ?>
					<tr class="front-table-row">
						<td title="{{ $submission->runid }}">
							@if($roleCheck->is("able-view-code", $param))
								<paper-button><a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif">
							@endif
							{{ $submission->runid }}
							@if($roleCheck->is("able-view-code", $param))
								</a></paper-button>
							@endif
						</td>
						<td>
							@if($roleCheck->is("able-view-code", $param))
								<paper-button><a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif" class="custom-word">
							@endif
							{{ substr($submission->submit_time, 2) }}
							@if($roleCheck->is("able-view-code", $param))
								</a></paper-button>
							@endif
						</td>
						<td>
							<paper-button><a href="/profile/{{ $submission->uid }}" class="custom-word">{{ $submission->uid }}</a></paper-button>
						</td>
						<td>
							<paper-button><a href="/profile/{{ $submission->uid }}" class="custom-word">{{ $submission->userName }}</a></paper-button>
						</td>
						<td>
							<paper-button><a href="/profile/{{ $submission->uid }}" class="custom-word">{{ $submission->nickname }}</a></paper-button>
						</td>
						<td class="text-left">
							&nbsp;{{ $submission->problemTitle }}
						</td>




						<td>
							@if($roleCheck->is("able-view-code", $param))
								<span onclick="window.location.href='/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif'"
							@else
								<span
							@endif
							@if($submission->result=="Accepted")
								class="label label-success"><span class="glyphicon glyphicon-ok"></span>Accepted
							@elseif($submission->result=="Compile Error")
								class="label label-default">Compile Error
							@elseif($submission->result=="Wrong Answer")
								class="label label-danger">Wrong Answer
							@elseif($submission->result=="Pending")
								class="label label-info">Pending
							@elseif($submission->result=="Rejudging")
								class="label label-info">Rejudging
							@else
								class="label label-warning">{{$submission->result}}
							@endif
							</span>
							@if($submission->result=="Accepted")
								@if(isset($submission->sim->similarity) && $roleCheck->is("admin"))
									<span class="label label-primary" title="runid:{{ $submission->sim->sim_runid }}" onclick="window.location.href='/status/sim?left={{ $submission->sim->runid }}&right={{ $submission->sim->sim_runid }}'" style="margin-left:5px">{{ $submission->sim->similarity }}%</span>
								@elseif(isset($submission->sim->similarity))
									<label class="label label-primary" title="runid:{{ $submission->sim->sim_runid }}" style="margin-left:5px">{{ $submission->sim->similarity }}%</label>
								@endif
							@endif
						</td>
						<td>
							@if($roleCheck->is("able-view-code", $param))
								<paper-button><a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif">
							@endif
							{{ $submission->lang }}
							@if($roleCheck->is("able-view-code", $param))
								</a></paper-button>
							@endif
						</td>
						<td>
							@if($roleCheck->is("able-view-code", $param))
								<paper-button><a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif">
							@endif
							@if($submission->exec_mem < 1024)
								{{ $submission->exec_mem }} Byte
							@elseif($submission->exec_mem < 1024*1024)
								{{ (int)($submission->exec_mem / 1024) }} KB
							@else
								{{ (int)($submission->exec_mem / 1024 / 1024) }} MB
							@endif
							@if(Request::session()->get('uid') == $submission->uid || $roleCheck->is("admin"))
								</a></paper-button>
							@endif
						</td>
						<td>
							@if($roleCheck->is("able-view-code", $param))
								<paper-button><a href="/status/{{ $submission->runid }}@if(isset($contest))?c={{ $contest->contest_id }}&p={{ $submission->contestProblemId }} @endif">
							@endif
								@if($submission->exec_time < 1)
									{{ (int)($submission->exec_time * 1000) }}ms
								@else
									{{ $submission->exec_time }}s
								@endif
							@if($roleCheck->is("able-view-code", $param))
								</a></paper-button>
							@endif
						</td>
						@if($roleCheck->is('admin'))
							<td>
								<form method="post" action="/rejudge/{{ $submission->runid }}">
									{{ csrf_field() }}
									<input class="btn btn-danger status-list-btn" type="submit" value="Rejudge"/>
								</form>
							</td>
						@endif
					</tr>
				@endforeach
			@endif
		</table>
		<ul class="pager" role="fanye">
			@if(!isset($firstPage))
				@if(!isset($contest))
					<li><a href="/status/p/{{ $page_id - 1 }}{{ $queryStr }}">&laquo;Previous</a></li>
				@else
					<li><a href="/contest/{{ $contest->contest_id }}/status/p/{{ $page_id - 1 }}{{ $queryStr }}">&laquo;Previous</a></li>
				@endif
			@endif
			@if(!isset($lastPage))
				@if(!isset($contest))
					<li><a href="/status/p/{{ $page_id + 1 }}{{ $queryStr }}">&nbsp;&nbsp;&nbsp;Next&nbsp;&nbsp;&nbsp;&raquo;</a></li>
				@else
					<li><a href="/contest/{{ $contest->contest_id }}/status/p/{{ $page_id + 1 }}{{ $queryStr }}">&nbsp;&nbsp;&nbsp;Next&nbsp;&nbsp;&nbsp;&raquo;</a></li>
				@endif
			@endif
		</ul>
	</div>
	@include("layout.footer")
	<script type="text/javascript">

		function freshResult()
		{
			var tableObj = window.document.getElementById("statuslist")
			var rows = tableObj.rows;
			for(var i = 1; i < rows.length; i++)
			{
				var run_id = rows[i].cells[0].title;
				var result = rows[i].cells[6].innerHTML;
				var resultObj = rows[i].cells;
				if(result.indexOf('Pending') != -1 || result.indexOf('Rejudging') != -1)
				{
					fetchResult(run_id, resultObj);
				}
			}
		}

		function fetchResult(run_id, resultObj) {
			console.log("run_id = " + run_id);
			$.ajax({
				url: "/ajax/submission",
				type: "GET",
				data: {
					run_id: run_id
				},
				dataType: "json",
			}).done(function(json){
				console.log(json);
				var tmpResult;
				var tmpObj = resultObj[6].innerHTML;
				/* if it's a span, then do not add anchor, else add anchor href */
				if(tmpObj.charAt(1)=='s')
					tmpResult = "<span ";
				else
					tmpResult = "<span onclick='window.location.href=\"/status/" + run_id + "@if(isset($contest))?c={{ $contest->contest_id }}&p=" + json.cpid + " @endif\"' ";

				if(json.result == "Accepted")
					tmpResult = tmpResult + "class=\"label label-success\"><span class='glyphicon glyphicon-ok'></span>Accepted";
				else if(json.result == "Wrong Answer")
					tmpResult = tmpResult + "class=\"label label-danger\">Wrong Answer";
				else if(json.result == "Compile Error")
					tmpResult = tmpResult + "class=\"label label-default\">Compile Error";
				else if(json.result == "Pending")
					tmpResult = tmpResult + "class=\"label label-info\">Pending";
				else if(json.result == "Rejudging")
					tmpResult = tmpResult + "class=\"label label-info\">Rejudging";
				else
					tmpResult = tmpResult + "class=\"label label-warning\">" + json.result;

				if(tmpObj.charAt(1)=='s')
					tmpResult = tmpResult + "</span>";
				else
					tmpResult = tmpResult + "</span>";

				resultObj[6].innerHTML = tmpResult;

				if(json.exec_mem > 1024 && json.exec_mem < 1024 * 1024)
					json.exec_mem = json.exec_mem / 1024 + " KB";
				else if(json.exec_mem >= 1024 * 1024)
					json.exec_mem = parseInt(json.exec_mem / 1024 / 1024 | 0) + "MB";
				else
					json.exec_mem = parseInt(json.exec_mem | 0) + " Byte";

				if(json.exec_time < 1)
					json.exec_time = json.exec_time * 1000 + "ms";
				else
					json.exec_time = json.exec_time + "s";
				if(tmpObj.charAt(1)!='s'){
					tmpResult = "<span window.location.href='\"/status/" + run_id + "@if(isset($contest))?c={{ $contest->contest_id }}&p=" + json.cpid + " @endif\"'>" + json.exec_mem + "</span>";
				}
				else{
					tmpResult = json.exec_mem;
				}
				resultObj[8].innerHTML = tmpResult;
				if(tmpObj.charAt(1)!='s'){
					tmpResult = "<span window.location.href='\"/status/" + run_id + "@if(isset($contest))?c={{ $contest->contest_id }}&p=" + json.cpid + " @endif\"'>" + json.exec_time + "</span>";
				}
				else{
					tmpResult = json.exec_time;
				}
				resultObj[9].innerHTML = tmpResult;
			})
		}

		setInterval("freshResult()", 1000);

	</script>
</body>
</html>