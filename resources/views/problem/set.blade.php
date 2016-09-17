<!doctype html>
<html>
<head>
	<title>Edit Problem</title>
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
	<style>
		.file_box {
			position: relative;
			width: 300px;
			display: inline-block;
			padding-top: 5px;
		}
		.file {
			position: absolute;
			top:0;
			left: 188px;
			width: 60px;
			opacity: 0;
		}
		.file_chose {
			border:1px solid #CDCDCD;
			width: 60px;
			height: 25px;
			padding: 3px;
		}
		.score_set {
			width: 180px;
		}
	</style>
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
				<h4>Problem Description</h4>
				<textarea class="form-control problem_add_wsg" name="description">{{ $problem->description or "" }}</textarea>
				<h4>Memory Limit (KB)</h4>
				<input class="form-control" name="mem_limit" type="text" value="{{ $problem->mem_limit }}" />
				<h4>Time Limit (S)</h4>
				<input class="form-control" name="time_limit" type="text" value="{{ $problem->time_limit }}" />
				<h4>Output Limit (KB)</h4>
				<input class="form-control"name="output_limit" type="text" value="{{ $problem->output_limit }}" />
				<h4>Input</h4>
				<textarea class="form-control problem_add_wsg"name="input">{{ $problem->input or "" }}</textarea>
				<h4>Output</h4>
				<textarea class="form-control problem_add_wsg" name="output">{{ $problem->output or "" }}</textarea>
				<h4>Sample Input</h4>
				<textarea class="form-control" name="sample_input" >{{ $problem->sample_input or ""}}</textarea>
				<h4>Sample Output</h4>
				<textarea class="form-control" name="sample_output">{{ $problem->sample_output or "" }}</textarea>
				<h4>Source</h4>
				<textarea class="form-control" name="source">{{ $problem->source or "" }}</textarea>
				<h4>Testcase</h4>
				<a href="javascript:addTestCase()" class="btn btn-grey">Add Testcase</a>
				@if($testcases == NULL)
					<div class="text-center problem-b-warning-box">You do not have any testcase here</div>
					<h5><b>Testcase 1</b></h5>
					Set Score: &nbsp;&nbsp;<input type="number" min="0" max="100" class="score_set" placeholder="未设置"><br/>
					<span>Upload Input File 1: &nbsp;&nbsp;&nbsp;</span>
					<div class="file_box">
						<input class="custom-word text_field" id="text_filed_i1" type="text" placeholder="未选择文件" />
						<a class="btn btn-grey file_chose">浏览</a>
						<input name="input_file[]" class="file" type="file" onchange="document.getElementById('text_filed_i1').value = this.value"/>
					</div>
					<br/>
					<span>Upload Output File 1: &nbsp;</span>
					<div class="file_box">
						<input class="custom-word text_field" id="text_filed_o1" type="text" placeholder="未选择文件" />
						<a class="btn btn-grey file_chose">浏览</a>
						<input name="input_file[]" class="file" type="file" onchange="document.getElementById('text_filed_o1').value = this.value"/>
					</div>
				@else
					@foreach($testcases as $testcase)
					<div id="t_{{ $testcase->rank }}">
						<h5><b>Testcase {{ $testcase->rank }}</b></h5>
						<div>
							Score: <br/>
							Input File: <a href="/storage/testdata?file={{ $testcase->input_file_name }}">{{ $testcase->input_file_name }}</a><br/>
							md5sum: {{ $testcase->md5sum_input }}</div>
						<div>
							Output File: <a href="/storage/testdata?file={{ $testcase->output_file_name }}">{{ $testcase->output_file_name }}</a><br/>
							md5sum: {{ $testcase->md5sum_output }}</div>
						Set Score: &nbsp;&nbsp;<input type="number" min="0" max="100" class="score_set" placeholder="重新设置"><br/>
						<span>Upload Input File {{ $testcase->rank }}: &nbsp;&nbsp;&nbsp;</span>
						<div class="file_box">
							<input class="custom-word text_field" id="text_filed_i{{ $testcase->rank }}" type="text" placeholder="重新选择文件" />
							<a class="btn btn-grey file_chose">浏览</a>
							<input name="input_file[]" class="file" type="file" onchange="document.getElementById('text_filed_i{{ $testcase->rank }}').value = this.value"/>
						</div>
						<br/>
						<span>Upload Output File {{ $testcase->rank }}: &nbsp;</span>
						<div class="file_box">
							<input class="custom-word text_field" id="text_filed_o{{ $testcase->rank }}" type="text" placeholder="重新选择文件" />
							<a class="btn btn-grey file_chose">浏览</a>
							<input name="input_file[]" class="file" type="file" onchange="document.getElementById('text_filed_o{{ $testcase->rank }}').value = this.value"/>
						</div>
					</div>
					@endforeach
				@endif
				<div class="back-testcase-add-list"></div>
				<div class="problem-b-submit">
					<input class="btn btn-default pull-right" type="submit" value="Save" />
				</div>
			</form>
		</div>
	</div>
	@include('layout.wysiwyg_foot')
	<script type="text/javascript">
		@if($testcases == NULL)
		var count = 2;
		@else
		var count = {{ $testcases->count()+1 }}
		@endif
		function addTestCase() {
			var testCaseItem = '<div id=t_' + count + '>' +
					'<h5><b>Testcase '+ count +'</b></h5>'+
					'Set Score: &nbsp;&nbsp;<input type="number" min="0" max="100" class="score_set" placeholder="重新设置"><br/>'+
					'<span>Upload Input File '+ count +': &nbsp;&nbsp;&nbsp;</span>'+
					'<div class="file_box">'+
					'<input class="custom-word text_field" id="text_filed_i'+ count +'" type="text" placeholder="重新选择文件" />'+
					'<a class="btn btn-grey file_chose">浏览</a>'+
					'<input name="input_file[]" class="file" type="file" onchange="document.getElementById(\'text_filed_i'+ count +'\').value = this.value"/>'+
					'</div>'+
					'<br/>'+
					'<span>Upload Output File ' + count + ': &nbsp;</span>'+
					'<div class="file_box">'+
					'<input class="custom-word text_field" id="text_filed_o' + count + '" type="text" placeholder="重新选择文件" />'+
					'<a class="btn btn-grey file_chose">浏览</a>'+
					'<input name="input_file[]" class="file" type="file" onchange="document.getElementById(\'text_filed_o'+ count +'\').value = this.value"/>'+
					'</div>'+
				'<a href="javascript:delTestCase(' + count + ')">Delete Testcase</a>' +
				'</div>';
			$('.back-testcase-add-list').append(testCaseItem);
			count++;
		}
		function delTestCase(divId) {
			$('#t_' + divId).remove();
		}
	</script>
</body>
</html>
