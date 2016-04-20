<!doctype html>
<html>
<head>
<title>Training</title>
@include("layout.head")
<link rel="stylesheet" href="/css/main.css">
<script type="text/javascript">
$(function(){
	$("#dashboard_training").addClass("dashboard_subnav_active");
})
</script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="col-xs-10">
<h3 class="text-center">Training</h3>
<div class="dashboard_problem_table">
<a class="btn btn-default" href = '/dashboard/training/add'>Add Training</a>
<table class="table table-bordered table-hover " id="dashboard_problem_list">
	<thead>
		<th class="text-center" id="dashboard_contest_id">Training ID</th>
		<th class="text-center" id="dashboard_contest_name">Training Name</th>
		<th class="text-center" id="dashboard_contest_operation">Operation</th>
	</thead>
	@for($i = 0; $i < $trainNum; $i++)
		<tr>
		<td class="text-center">{{ $i + 1 }}</td>
		<td class="text-center"><a href = '/training/{{ $training[$i]->train_id }}'>{{ $training[$i]->train_name }}</a></td>
		<td class="text-center">
			<a class="btn btn-default" href="/dashboard/training/{{ $training[$i]->train_id }}">Edit Training</a>
			<form method="post" action="/dashboard/training/{{ $training[$i]->train_id }}"class="dashboard_problem_table_form" onsubmit = "return validator()">
				{{ method_field('DELETE') }}
				{{ csrf_field() }}
				<input type="submit"class="btn btn-default" value="delete training"/>
			</form>
		</td>
		</tr>
	@endfor
</table>
</div>
</body>
<script language="Javascript">
	function validator()
	{
		if(confirm("confirm")==true)
			return true;
		else
			return false;
	}
</script>
</html>