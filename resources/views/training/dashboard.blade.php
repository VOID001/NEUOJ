<!doctype html>
<html>
<head>
	<title>Manage Training</title>
	@include("layout.head")
	@include("layout.dashboard_header")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#dashboard_training").addClass("dashboard-subnav-active");
		})
	</script>
</head>
<body>
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h3 class="custom-heading">Training</h3>
		<div class="back-list">
			<a class="btn btn-grey" href = '/dashboard/training/add'>Add Training</a>
			<table class="table table-bordered table-hover custom-list">
				<thead>
					<th class="text-center">Training ID</th>
					<th class="text-center">Training Name</th>
					<th class="text-center">Operation</th>
				</thead	>
				@for($i = 0; $i < $trainNum; $i++)
					<tr>
						<td>{{ $i + 1 }}</td>
						<td><a href = '/training/{{ $training[$i]->train_id }}'>{{ $training[$i]->train_name }}</a></td>
						<td>
							<a class="btn btn-grey" href="/dashboard/training/{{ $training[$i]->train_id }}">Edit Training</a>
							<form class="back-btn-form" method="post" action="/dashboard/training/{{ $training[$i]->train_id }}" onsubmit = "return confirm('确认删除')">
								{{ method_field('DELETE') }}
								{{ csrf_field() }}
								<input type="submit"class="btn btn-grey" value="delete training"/>
							</form>
						</td>
					</tr>
				@endfor
			</table>
		</div>
	</body>
</html>