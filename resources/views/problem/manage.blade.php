<!doctype html>
<html>
<head>
	<title>Manage Problem</title>
	@include("layout.head")
	@include("layout.dashboard_header")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/extendPagination.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#dashboard_problem").addClass("dashboard-subnav-active");
			var targetHerf = "/dashboard/problem/p/";
			$("#callBackPager").extendPagination({
				totalPage : {{ $page_num }},
				showPage : 5,
				pageNumber : {{ $page_id }}
			},targetHerf);
			$('input[type=file]').change(function() {
				$(this).siblings('input[type=text]').val($(this).val());
			});
		})
	</script>
</head>
<body>
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h3 class="custom-heading" id="dashboard-custom-heading">Problem</h3>
		<div class="back-list">
			<ol class="problem-b-error-box">
				@if(!$errors->isEmpty())
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				@endif
			</ol>
			<a class="btn btn-grey" href="/dashboard/problem/add/">Add Problem</a>
			<form class="back-btn-form pull-right" enctype="multipart/form-data" action="/dashboard/problem/import" method="POST">
				{{ csrf_field() }}
				<div class="file-input">
					<btn class="btn">选择文件</btn>
					<input class="custom-word" type="text" placeholder="未选择文件" />
					<input name="xml" type="file" value="XML Format Data" />
				</div>
				<input class="btn btn-grey"type="submit" value="Import" />
			</form>
			<div>{{ $status or "" }}</div>
			<table class="table table-bordered table-hover custom-list">
				<thead>
					<tr>
						<th class="text-center" width="10%">Problem ID</th>
						<th width="18%">Title</th>
						<th class="text-center" width="12%">Visibility_Lock</th>
						<th class="text-center" width="16%">Created_at</th>
						<th class="text-center" width="16%">Updated_at</th>
						<th class="text-center" width="16%">Operate</th>
					</tr>
				</thead>
				@foreach($problems as $problem)
					<tr>
						<td>{{ $problem->problem_id }}</td>
						<td class="text-left">{{ $problem->title }}</td>
						<td>
							@if($problem->visibility_locks == 0)
								<a class="btn btn-success problem-manage-lock-btn custom-word" href="/dashboard/problem/{{ $problem->problem_id }}/visibility">Lock({{ $problem->used_times }})</a>
							@else
								<a class="btn btn-danger problem-manage-lock-btn custom-word" href="/dashboard/problem/{{ $problem->problem_id }}/visibility">Unlock({{ $problem->used_times }})</a>
							@endif
						</td>
						<td>{{ $problem->created_at }}</td>
						<td>{{ $problem->updated_at }}</td>
						<td>
							<a class="btn btn-grey" href="/dashboard/problem/{{ $problem->problem_id }}">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>
							<form class="back-btn-form" action="/dashboard/problem/{{ $problem->problem_id }}" method="POST">
								{{ method_field('DELETE') }}
								{{ csrf_field() }}
							<input class="btn btn-grey" type="submit" value="Delete"/>
							</form>
						</td>
					</tr>
				@endforeach
			</table>
			<div class="text-center" id="callBackPager"></div>
		</div>
	</div>
</body>
</html>