@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Ranklist</title>
	@include("layout.head")
	<script type="text/javascript" src="/js/jquery.imageloader.js"></script>
	<link rel="stylesheet" href="/css/main.css">
	<link rel="stylesheet" href="/css/contest.css">
	<script type="text/javascript">
		$(function(){
			$("#contest").addClass("active");
			var problemLength = $('.contest-ranklist-problem').length;
			var maxShowProblemLength = 7;
			if(problemLength > maxShowProblemLength) {
				$('#contest-ranklist-table-responsive').css('overflow-x', 'scroll');
			}
		})
	</script>
</head>
<body>
	@include("layout.header")
	<h3 class="custom-heading">Ranklist</h3>
	<div class="front-big-container">
		<div class="front-time-box">
			<a class="btn btn-info" href="/contest/{{ $contest_id }}">&nbsp;&nbsp;Back&nbsp;&nbsp;</a>
			@if($roleCheck->is("admin"))
				<a class="btn btn-info" href="/contest/{{ $contest_id }}/ranklist/export">&nbsp;&nbsp;EXPORT&nbsp;&nbsp;</a>
			@endif
		</div>
		<div id="contest-ranklist-table-responsive">
			<table class="table table-striped table-bordered custom-list">
			<thead class="front-green-thead">
				<th class="text-center" id="contest-ranklist-rank">Rank</th>
				<th class="text-center" id="contest-ranklist-id">
					@if($roleCheck->is("admin"))
						学号
					@else
						Avatar
					@endif
				</th>
				<th class=" text-center" id="contest-ranklist-name">
					@if($roleCheck->is("admin"))
						真实姓名
					@else
						Nick name
					@endif
				</th>
				<!-- <th class="text-center" id="contest-ranklist-solve">Solve</th> -->
				<th class="text-center" id="contest-ranklist-solve">Score</th>
				<th class="text-center" id="contest-ranklist-penalty">Penalty</th>
				@foreach($problems as $problem)
					<th class="text-center contest-ranklist-problem">
						{{ $problem->problem_title }}
					</th>
				@endforeach
			</thead>
				@foreach($users as $user)
					<tr class="front-table-row">
						<td>
							{{ $counter++ }}
						</td>
						<td>
							<paper-button><a class="custom-word" href="/profile/{{ $user->uid }}">
								@if($roleCheck->is("admin"))
									{{ $user->stu_id }}
								@else
									<img class="loader contest-ranklist-img" src="/image/loading.gif" data-src="/avatar/{{$user->uid}}" />
								@endif
							</a></paper-button>
						</td>
						<td>
							<paper-button>
								@if($roleCheck->is("admin"))
									<a class="custom-word" href="/profile/{{ $user->uid }}" title="{{ $user->realname }}">
										{{ $user->realname }}
									</a>
								@else
									<a class="custom-word" href="/profile/{{ $user->uid }}" title="{{ $user->nickname }}">
										{{ $user->nickname }}
									</a>
								@endif
							</paper-button>
						</td>
						<!--
						<td>
							{{ $user->infoObj->totalAC }}
						</td>-->
						<td>
							{{ $user->infoObj->totalScore }}
						</td>
						<td>
							{{--the total penalty--}}
							@if(intval($user->infoObj->totalPenalty / 60 / 60)<=9)
								0{{intval($user->infoObj->totalPenalty / 60 / 60)}}
							@else{{intval($user->infoObj->totalPenalty / 60 / 60)}}
							@endif
							: {{ substr(strval($user->infoObj->totalPenalty % 3600 / 60 + 100), 1, 2) }} : {{ substr(strval($user->infoObj->totalPenalty % 60 + 100), 1, 2) }}
						</td>
						{{--every problem's result--}}
						@foreach($problems as $problem)
							<td class="contest-ranklist-btn">
								{{--first blood--}}
								@if(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "First Blood")
									<div class="btn btn-primary">
										@if( intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) <= 9)
											0{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}
										@else
											{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}
										@endif
										: {{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 3600 / 60 + 100), 1, 2) }} : {{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 60 + 100), 1, 2) }}
										@if($user->infoObj->penalty[$problem->contest_problem_id])
											({{ $user->infoObj->penalty[$problem->contest_problem_id] }})
										@endif
										</div>
								{{--only accepted--}}
								@elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && $user->infoObj->result[$problem->contest_problem_id] == "Accepted")
									<div class="btn btn-success">
										@if( intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) <=9)
											0{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}
										@else
											{{ intval($user->infoObj->time[$problem->contest_problem_id] / 60 / 60) }}
										@endif
										: {{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 3600 / 60 + 100), 1, 2) }} : {{ substr(strval($user->infoObj->time[$problem->contest_problem_id] % 60 + 100), 1, 2) }}
										@if($user->infoObj->penalty[$problem->contest_problem_id])
											({{ $user->infoObj->penalty[$problem->contest_problem_id] }})
										@endif
									</div>
								@elseif(isset($user->infoObj->result[$problem->contest_problem_id]) && ($user->infoObj->result[$problem->contest_problem_id] == "Rejudging" || $user->infoObj->result[$problem->contest_problem_id] == "Pending"))
									<div class="btn btn-default">
										Pending/Rejudging
									</div>
								@elseif(isset($user->infoObj->result[$problem->contest_problem_id]))
									<div class="btn btn-danger text-center">
										({{ $user->infoObj->penalty[$problem->contest_problem_id] }})<b>{{ $user->infoObj->scoreList[$problem->contest_problem_id] }}</b>
									</div>
								@endif
							</td>
						@endforeach
					</tr>
				@endforeach
			</table>
		</div>
	</div>
	@include("layout.footer")
	<script type="text/javascript">
		$.imageloader.queueInterval = 300;
		$(document).ready(function () {
			$('body').imageloader(
					{
						selector: '.loader',
						callback: function (elm) {
							$(elm).fadeIn()
						}
					}
			);
		});
	</script>
	@include("layout.footer")
</body>
</html>
