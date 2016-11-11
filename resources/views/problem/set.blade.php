<!doctype html>
<html>
<head>
	<title>Edit Problem</title>
	@include("layout.head")
	@include("layout.dashboard_header")
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
		<h2 class="custom-heading">Edit Problem</h2>
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
			<form action="/dashboard/problem/{{ $problem->problem_id }}" method="post" enctype="multipart/form-data">
				{{ csrf_field() }}
				<h4>Problem Title</h4>
				<input class="form-control" name="title" type="text" value="{{ $problem->title }}" />
				<h4>Difficulty</h4>
				<input class="form-control" name="difficulty" type="text" value="{{ $problem->difficulty }}" />
				<h4>Problem Description</h4>
				<textarea class="form-control summernote" name="description">{{ $problem->description or "" }}</textarea>
				<h4>Memory Limit (KB)</h4>
				<input class="form-control" name="mem_limit" type="text" value="{{ $problem->mem_limit }}" />
				<h4>Time Limit (S)</h4>
				<input class="form-control" name="time_limit" type="text" value="{{ $problem->time_limit }}" />
				<h4>Output Limit (KB)</h4>
				<input class="form-control"name="output_limit" type="text" value="{{ $problem->output_limit }}" />
				<h4>Input</h4>
				<textarea class="form-control summernote"name="input">{{ $problem->input or "" }}</textarea>
				<h4>Output</h4>
				<textarea class="form-control summernote" name="output">{{ $problem->output or "" }}</textarea>
				<h4>Sample Input</h4>
				<textarea class="form-control" name="sample_input" >{{ $problem->sample_input or ""}}</textarea>
				<h4>Sample Output</h4>
				<textarea class="form-control" name="sample_output">{{ $problem->sample_output or "" }}</textarea>
				<h4>Source</h4>
				<textarea class="form-control" name="source">{{ $problem->source or "" }}</textarea>
				<!-- Now only support single testcase -->
				@if($testcases == NULL)
					<h4>Upload Input File</h4>
					<div class="text-center problem-b-warning-box">No Testcase!</div>
					<div class="file-input">
						<btn class="btn">选择文件</btn>
						<input class="custom-word" type="text" placeholder="未选择文件" />
						<input name="input_file[]" type="file" />
					</div>
					<h4>Upload Output File</h4>
					<div class="text-center problem-b-warning-box">No Testcase!</div>
					<div class="file-input">
						<btn class="btn">选择文件</btn>
						<input class="custom-word" type="text" placeholder="未选择文件" />
						<input name="output_file[]" type="file" />
					</div>
				@else
					@foreach($testcases as $testcase)
						<h4>Upload Input File</h4>
						<div>Input File: <a href="/storage/testdata?file={{ $testcase->input_file_name }}">{{ $testcase->input_file_name }}</a> md5sum: {{ $testcase->md5sum_input }}</div>
						<div class="file-input">
							<btn class="btn">选择文件</btn>
							<input class="custom-word" type="text" placeholder="未选择文件" />
							<input name="input_file[]" type="file" />
						</div>
						<h4>Upload Output File</h4>
						<div>Output File: <a href="/storage/testdata?file={{ $testcase->output_file_name }}">{{ $testcase->output_file_name }}</a> md5sum: {{ $testcase->md5sum_output }}</div>
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
