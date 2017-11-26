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
			var maxShowProblemLength = 13;
			if(problemLength > maxShowProblemLength) {
				$('#contest-ranklist-table-responsive').css('overflow-x', 'scroll');
			}
			var problem_shorttitles = new Array ('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
			var titleCount = 1;
			for( var i = 0;i < problemLength;i++){
				var titleItem = problem_shorttitles[i];
				$('#title_'+titleCount).append(titleItem);
				titleCount++;
			}
		})
	</script>
</head>
<body>
	@include("layout.header")
	<h3 class="custom-heading">Ranklist</h3>
	<div class="contest-ranklist-table">
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
				<!--<th class="text-center" id="contest-ranklist-id">
					@if($roleCheck->is("admin"))
						学号
					@else
						Avatar
					@endif
				</th>-->
				@if($roleCheck->is("admin"))
				<th class="text-center" id="contest-ranklist-id">学号</th>
				@endif
				<th class=" text-center" id="contest-ranklist-name">
					@if($roleCheck->is("admin"))
						真实姓名
					@else
						Nick name
					@endif
				</th>
				<th class="text-center" id="contest-ranklist-solve">Solve</th>
				<th class="text-center" id="contest-ranklist-penalty">Penalty</th>
				@foreach($problems as $problem)
				<th class="text-center contest-ranklist-problem">
					<a  href="/contest/{{ $contest_id }}/problem/{{ $problem->contest_problem_id }}">
						<div id="title_{{ $problem->contest_problem_id }}"></div>
					</a>
				</th>
				@endforeach
			</thead>
				@foreach($ranklist as $user)
					<tr class="front-table-row">
						<td>
							{{ $counter++ }}
						</td>
						<!--<td>
							<paper-button><a class="custom-word" href="/profile/{{ $user->uid }}">
								@if($roleCheck->is("admin"))
									{{ $user->stu_id }}
								@else
									<img class="loader contest-ranklist-img" src="/image/loading.gif" data-src="/avatar/{{$user->uid}}" />
								@endif
							</a></paper-button>
						</td>-->
						@if($roleCheck->is("admin"))
						<td>
							<paper-button><a class="custom-word" href="/profile/{{ $user->uid }}">
									{{ $user->stu_id }}
							</a></paper-button>
						</td>
						@endif
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
						<td>
							{{ $user->total_ac }}
						</td>
						<td>
							{{--the total penalty--}}
							{{ $user->total_penalty }}
						</td>
						{{--every problem's result--}}
						@foreach($problems as $problem)
							<td class="contest-ranklist-btn">
								{{--first blood--}}
                                @if(isset($user->result_list[$problem->contest_problem_id]))
								@if(isset($user->result_list[$problem->contest_problem_id]) && $user->result_list[$problem->contest_problem_id] == "First Blood")
									<div class="btn btn-primary">
                                        {{ $user->penalty_list[$problem->contest_problem_id]['time'] }}
                                        @if($user->penalty_list[$problem->contest_problem_id]['penalty'] != 0)
                                        ({{ $user->penalty_list[$problem->contest_problem_id]['penalty'] }})
                                        @endif
									</div>
								{{--only accepted--}}
								@elseif(isset($user->result_list[$problem->contest_problem_id]) && $user->result_list[$problem->contest_problem_id] == "Accepted")
									<div class="btn btn-success">
                                        {{ $user->penalty_list[$problem->contest_problem_id]['time'] }}
                                        @if($user->penalty_list[$problem->contest_problem_id]['penalty'] != 0)
                                        ({{ $user->penalty_list[$problem->contest_problem_id]['penalty'] }})
                                        @endif
									</div>
								@elseif(isset($user->result_list[$problem->contest_problem_id]) && $user->result_list[$problem->contest_problem_id] == "Rejudging" || $user->result_list[$problem->contest_problem_id] == "Pending")
									<div class="btn btn-default">
										Pending/Rejudging
									</div>
								@elseif(isset($user->result_list[$problem->contest_problem_id]))
									<div class="btn btn-danger text-center">
										({{ $user->penalty_list[$problem->contest_problem_id]['penalty'] }})
									</div>
								@endif
                                @endif
							</td>
						@endforeach
					</tr>
				@endforeach
			</table>
		</div>
	</div>
	@include("layout.footer")
	<!--<script type="text/javascript">
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
	</script>-->
</body>
</html>