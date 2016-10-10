<!doctype html>
<html>
<head>
	<title>Add Problem</title>
	@include("layout.head")
	@include("layout.wysiwyg_head")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#dashboard_problem").addClass("dashboard-subnav-active");
			$('input[type=file]').change(function() {
				$(this).siblings('input[type=text]').val($(this).val());
			});
		})
	</script>
</head>
<body>
	@include("layout.dashboard_nav")
	<div class="back-container">
		<h2 class="custom-heading">Add Problem</h2>
		<div class="back-list">
			<ol class="problem-b-error-box">
				@if(!isset($error))
					@foreach($infos as $info)
						<li>{{ $info }}</li>
					@endforeach
				@else
					@foreach($errors as $error)
						<li>{{ $error }}</li>
					@endforeach
				@endif
			</ol>
			<form action="/dashboard/problem/add" method="post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<h4>Problem Title</h4>
				<input class="form-control" name="title" type="text" autocomplete="off" />
				<h4>Difficulty</h4>
				<input class="form-control" name="difficulty" type="text" value="" />
				<h4>Problem Description</h4>
				<textarea class="form-control problem_add_wsg" name="description"></textarea>
				<h4>Problem Visibility</h4>
				<input name="visibility_locks" type="radio" value = "0" checked/>Unlock
				<input name="visibility_locks" type="radio" value = "1"/>Lock
				<h4>Memory Limit (KB)</h4>
				<input class="form-control" name="mem_limit" type="text" value="65536" />
				<h4>Time Limit (S)</h4>
				<input class="form-control" name="time_limit" type="text" value="1" />
				<h4>Output Limit (KB)</h4>
				<input class="form-control" name="output_limit" type="text"value="5120" />
				<h4>Input</h4>
				<textarea class="form-control problem_add_wsg" name="input"></textarea>
				<h4>Output</h4>
				<textarea class="form-control problem_add_wsg" name="output"></textarea>
				<h4>Sample Input</h4>
				<textarea class="form-control" name="sample_input"></textarea>
				<h4>Sample Output</h4>
				<textarea class="form-control" name="sample_output"></textarea>
				<h4>Source</h4>
				<textarea class="form-control" name="source"></textarea>
				<!-- Now only support single testcase -->
				@if($testcases == NULL)
					<div class="text-center problem-b-warning-box">You do not have any testcase here</div>
					<h4>Upload Input File</h4>
					<div class="file-input">
						<btn class="btn">选择文件</btn>
						<input class="custom-word" type="text" placeholder="未选择文件" />
						<input name="input_file[]" type="file" />
					</div>
					<h4>Upload Output File</h4>
					<div class="file-input">
						<btn class="btn">选择文件</btn>
						<input class="custom-word" type="text" placeholder="未选择文件" />
						<input name="output_file[]" type="file" />
					</div>
				@else
					@foreach($testcases as $testcase)
						<h4>Upload Input File</h4>
						<div class="file-input">
							<btn class="btn">选择文件</btn>
							<input class="custom-word" type="text" placeholder="未选择文件" />
							<input name="input_file[]" type="file" />
						</div>
						<input class="file-input" name="input_file[]" type="file" />
						<h4>Upload Output File</h4>
						<div class="file-input">
							<btn class="btn">选择文件</btn>
							<input class="custom-word" type="text" placeholder="未选择文件" />
							<input name="output_file[]" type="file" />
						</div>
					@endforeach
				@endif
				<div class="problem-b-submit">
					<input class="btn btn-default pull-right" type="submit" value="Save" />
				</div>
			</form>
		</div>
	</div>
	@include('layout.wysiwyg_foot')
</body>
</html>