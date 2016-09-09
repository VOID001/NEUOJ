<!doctype html>
<html>
<head>
	<title>Problem List</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/extendPagination.js"></script>
	<script src="/js/searchFunction.js"></script>
	<script>
		$(function(){
			$("#problem").addClass("active");
			var targetHerf = "/problem/p/";
			$("#callBackPager").extendPagination({
				totalPage : {{ $page_num }},
				showPage : 5,
				pageNumber : {{ $page_id }}
			},targetHerf);
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
	<h3 class="custom-heading">Problem List</h3>
	<div class="front-container">
		<form id="problem-list-search-form" action="/problem/quick_access">
			<span>Quick Access: </span>
			<div class="search-container">
				<input class="form-control search-title problem-id" name="query" placeholder="Input ProblemID or Problem Title to Search" type="text" autocomplete="off" />
				<div class="search-option hidden"></div>
			</div>
			<input class="btn btn-info" type="submit" value="&nbsp;&nbsp;Go&nbsp;&nbsp;" />
		</form>
		<table class="table table-striped table-bordered custom-list">
			<thead>
				<tr>
					<th class="text-center" width="8%">Status</th>
					<th class="text-center" width="10%">Problem ID</th>
					<th width="30%">Title</th>
					<th class="text-center" width="10%">Difficulty</th>
					<th class="text-center" width="10%">AC/Submit</th>
					<th class="text-center" width="20%">Author</th>
					{{--<th class="text-center">Visibility_Lock(use for debug version)</th>--}}
				</tr>
			</thead>
			@if($problems != NULL)
				@foreach($problems as $problem)
					<tr class="front-table-row">
						<td>
							<span class="
							@if($problem->status == 'Y')
								glyphicon glyphicon-ok
							@elseif($problem->status == 'N')
								glyphicon glyphicon-remove
							@endif"></span>
						</td>
						<td>
							<paper-button><a href="/problem/{{ $problem->problem_id }}">{{ $problem->problem_id }}</a></paper-button>
						</td>
						<td>
							<paper-button><a class="text-left custom-word" href="/problem/{{ $problem->problem_id }}">&nbsp;{{ $problem->title }}</a></paper-button>
						</td>
						<td>{{ $problem->difficulty }}</td>
						<td>
							<paper-button><a href="/status/p/1?pid={{ $problem->problem_id }}">{{ $problem->ac_count. "/" . $problem->submission_count }}</a></paper-button>
						</td>
						<td>{{ $problem->author }}</td>
					</tr>
				@endforeach
			@endif
		</table>
		<div class="text-center" id="callBackPager"></div>
	</div>
	@include("layout.footer")
</body>
</html>