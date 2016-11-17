<!doctype html>
<html>
<head>
	<title>Set Training</title>
	@include("layout.head")
	@include("layout.dashboard_header")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/searchFunction.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#dashboard_training").addClass("dashboard-subnav-active");
		})
	</script>
</head>
<body>
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h3 class="custom-heading" id="dashboard-custom-heading">Set Training</h3>
		<form class="back-problem-form" action="/dashboard/training/{{ $train_info->train_id }}" method="post">
			{{ csrf_field() }}
			<table class="custom-table">
				@foreach($errors->all() as $error)
					<tr>
						<td colspan="2"><span class="label label-warning">{{ $error }}</span></td>
					</tr>
				@endforeach
				<tr>
					<td>Train Name</td>
					<td><input class="form-control" name="train_name" type="text" value="{{ $train_info->train_name }}" /></td>
				</tr>
				<tr>
					<td>Description</td>
					<td><input class="form-control" name="description" type="text" value="{{ $train_info->description }}" /></td>
				</tr>
			</table>
			<div class="text-center training-b-add-chapter">
				<a href="javascript:addChapter()">Add Chapter</a>
				<a href="javascript:deleteChapter()">Delete The Last Chapter</a>
			</div>
			@for($i = 1; $i <= $train_info->train_chapter; $i++)
				<div class="back-problem-add-list" id="chapter_{{ $i }}">
					<div class='text-center'>
					Chapter {{ $i }}
					<a href="javascript:addProblem({{ $i }})"> Add Problem</a>
					</div>
					@foreach($train_problem as $problem)
						@if($problem->chapter_id == $i)
							<div class="back-problem-add" id="p_{{ $problem->train_problem_id+1 }}">
							<input name="problem_chapter[]" type="hidden" value="{{ $i }}" />
							<span>Problem ID</span>
							<div class="search-container">
								<input class="form-control search-title problem-id training-b-problem-input" name="problem_id[]"  type="text" value="{{ $problem->problem_id }}" autocomplete="off" />
								<div class="search-option hidden"></div>
							</div>
							<span>Problem Name</span>
							<input class="form-control problem-title training-b-problem-input" name="problem_name[]" type="text" value="{{ $problem->problem_title }}" autocomplete="off" />
							<a href="javascript:deleteProblem({{ $i }}, {{ $problem->train_problem_id+1 }})">Delete Problem</a>
							</div>
						@endif
					@endforeach
				</div>
			@endfor
			<input class="center-block" id="train_chapter" name="train_chapter" type="hidden" value="{{ $train_info->train_chapter }}" />
			<input class="center-block" type="submit" value="Submit"/>
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
				bindSearchFunction(result, true);
				titleData = result;
			}
		});
		var chapterCount = {{ $train_info->train_chapter }} + 1;
		var problemCount = {{ $train_problem_count }};
		function addChapter() {
			var chapterItem = '<div class="back-problem-add-list" id="chapter_' + chapterCount + '"><div class="text-center">Chapter' + chapterCount + '<a href="javascript:addProblem(' + chapterCount + ')">  Add Problem </a></div></div>';
			$('#train_chapter').before(chapterItem);
			$('#train_chapter').val(chapterCount);
			chapterCount++;
		}
		function deleteChapter() {
			if(chapterCount > 1) {
				$('#chapter_' + (chapterCount - 1)).remove();
				chapterCount--;
				$('#train_chapter').val(chapterCount -1);
			}
		}
		function addProblem(chapter_id) {
			var problemItem = '<div class="back-problem-add" id=p_' + problemCount + '>' +
				'<span>Problem ID </span>' +
				'<input type="hidden" name="problem_chapter[]" value=' + chapter_id + '/>' +
				'<div class="search-container">' +
				'<input class="form-control search-title problem-id training-b-problem-input" type="text" name="problem_id[]" autocomplete="off" />' +
				'<div class="search-option hidden"></div>' +
				'</div>' +
				'<span> Problem Name </span>' +
				'<input class="form-control problem-title training-b-problem-input" type="text" name="problem_name[]" autocomplete="off" />' +
				'<a href="javascript:deleteProblem(' + chapter_id + ',' +problemCount + ')"> Delete Problem</a>' +
				'</div>'
				$('#chapter_' + chapter_id).append(problemItem);
			problemCount++;
			bindSearchFunction(titleData);
		}
		function deleteProblem(chapter_id, divId) {
			$('#chapter_' + chapter_id).children('#p_' + divId).remove();
		}
	</script>
</body>
</html>