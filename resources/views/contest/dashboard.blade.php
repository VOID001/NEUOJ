<!doctype html>
<html>
<head>
	<title>Manage Contest</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#dashboard_contest").addClass("dashboard-subnav-active");
		})
	</script>
</head>
<body>
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h3 class="custom-heading">Contest</h3>
		<div class="back-list">
			<a class="btn btn-grey" href="/dashboard/contest/add">New Contest</a>
			@if(isset($contests))
				<table class="table table-bordered table-hover custom-list">
					<thead>
						<th class="text-center" width="9%">Contest ID</th>
						<th class="text-center" width="12%">Contest Name</th>
						<th class="text-center" width="7%">Type</th>
						<th class="text-center" width="8%">Status</th>
						<th class="text-center" width="16%">Begin Time</th>
						<th class="text-center" width="16%">End Time</th>
						<th class="text-center" width="16%">Operation</th>
					</thead>
					@foreach($contests as $contest)
						<tr>
							<td>{{ $contest->contest_id }}</td>
							<td>{{ $contest->contest_name }}</td>
							<td>
								@if($contest->contest_type == 0)
									Public
								@elseif($contest->contest_type == 1)
									Private
								@else
									Register
								@endif
							</td>
							<td>{{ $contest->status }}</td>
							<td>{{ $contest->begin_time }}</td>
							<td>{{ $contest->end_time }}</td>
							<td>
								<a class="btn btn-grey" href="/dashboard/contest/{{ $contest->contest_id }}">&nbsp;&nbsp;Edit&nbsp;&nbsp;</a>
								<form class="back-btn-form" method="post" action="/dashboard/contest/{{ $contest->contest_id }}" onsubmit = "return confirm('确认删除')">
									{{ method_field('DELETE') }}
									{{ csrf_field() }}
									<input class="btn btn-grey" type="submit" value="delete" />
								</form>
							</td>
						</tr>
					@endforeach
				</table>
			@endif
		</div>
	</div>
</body>
</html>