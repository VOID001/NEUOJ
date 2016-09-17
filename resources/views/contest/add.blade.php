<!doctype html>
<html>
<head>
	<title>Add Contest</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/searchFunction.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#dashboard_contest").addClass("dashboard-subnav-active");
			if($('#public-radio')[0].checked) {
				$('#private-table').hide();
				$('#register-table').hide();
			}else if($('#private-radio')[0].checked) {
				$('#private-table').show();
				$('#register-table').hide();
			}else {
				$('#private-table').hide();
				$('#register-table').show();
			}
			$('#public-radio').click(function() {
				$('#private-table').slideUp();
				$('#register-table').slideUp();
			})
			$('#private-radio').click(function() {
				$('#private-table').slideDown();
				$('#register-table').slideUp();
			})
			$('#register-radio').click(function() {
				$('#private-table').slideUp();
				$('#register-table').slideDown();
			})
		})
	</script>
	<style>
		.student-list{
			margin-top:100px;
		}
		form {
			padding: 0;
			margin: 0;
		}
		#private-table table{
			margin: 0;
			width: 100%;
		}
		#tmp_form{
			position: absolute;
			top:80px;
			right: 80px;
		}
		.student-list-checkbox-table{
			max-height: 1000px;
			overflow-y: scroll;
		}
	</style>
</head>
<body >
	@include("layout.dashboard_nav")
	<div class="back-container">
	<form class="back-problem-form" action="/dashboard/contest/add" method="post">
		{{ csrf_field() }}
		<div class="contest-left">
		<h3 class="custom-heading">Add Contest</h3>
			<table class="custom-table">
				@foreach($errors->all() as $error)
					<tr>
						<td colspan="2"><span class="label label-warning">{{ $error }}</span></td>
					</tr>
				@endforeach
				<tr>
					<td>Contest Name</td>
					<td><input class="form-control" name="contest_name" type="text" value="{{ old('contest_name') }}" required /></td>
				</tr>
				<tr>
					<td>Begin Time</td>
					<td><input class="form-control" name="begin_time" type="datetime-local" value="{{ old('begin_time') }}" required /></td>
				</tr>
				<tr>
					<td>End Time</td>
					<td><input class="form-control" name="end_time" type="datetime-local" value="{{ old('end_time') }}" required /></td>
				</tr>
				<tr>
					<td>Contest Type</td>
					<td>
						<input id="public-radio" name="contest_type" type="radio" value="public" checked />public
						<input id="private-radio" name="contest_type" type="radio" value="private" />private
						<input id="register-radio" name="contest_type" type="radio" value="register" />register
					</td>
				</tr>
			</table>
			<div class="contest-b-private-table" id="private-table">
				<table class="custom-table">
					<tr>
						<td>Allowed Username</td>
						<td><textarea class="form-control resize-none" name="user_list" placeholder="Input the user name , seperate each with comma" rows="5"></textarea></td>
					</tr>
				</table>
			</div>
			<div id="register-table">
				<table class="custom-table">
					<tr>
						<td>Register Begin Time</td>
						<td><input class="form-control" type="datetime-local" name="register_begin_time" value="{{ old('register_begin_time') }}" /></td>
					</tr>
					<tr>
						<td>Register End Time</td>
						<td><input class="form-control" type="datetime-local" name="register_end_time" value="{{ old('register_end_time') }}" /></td>
					</tr>
				</table>
			</div>
			<div class="text-center training-b-add-chapter">
				<label>Select Problem</label>
				<a href="javascript:addProblem()">Add Problem</a>
			</div>
			<div class="back-problem-add-list text-center"></div>
			<input class="center-block" type="submit" value="Submit" />
		</div>

	</form>

	</div>
	<script>
		function getList() {
			var form = new FormData($("#tmp_form")[0]);
			$.ajax({
				url: '/ajax/memberlist',
				type: 'post',
				processData: false,
				contentType: false,
				data: form,
				success: function (data) {
					//alert(data);
					$(".student-list-checkbox").children('tr').remove();
					var datas = new Array();
					datas = data.split(",");
					for(var i=0;i<datas.length-1;i++) {
						var student = '<tr>' +
								'<td><input type="checkbox" value="' + datas[i] + '" checked></td>' +
								'<td>' + (i+1) + '</td>' +
								'<td>' + datas[i] + '</td>'+
								'</tr>';
						$(".student-list-checkbox").append(student);
					}
				}
			});
		}
		function appendRight(){
			var right = '<div class="col-md-6 student-list contest-right">'+
					'<div class="student-list">'+
						'<div class="student-list-checkbox-table">'+
							'<table class="table table-bordered table-hover ">'+
								'<thead>'+
									'<tr>'+
										'<th>选择</th>'+
										'<th>顺序</th>'+
										'<th>指定列</th>'+
									'</tr>'+
								'</thead>'+
								'<tbody class="student-list-checkbox">'+
								'</tbody>'+
							'</table>'+
						'</div>'+
					'</div>'+
					'</div>';
			$(".back-problem-form").append(right);
			var form = '<form enctype="multipart/form-data" id="tmp_form">'+
					'{{ csrf_field() }}'+
                    '<h4>Import user list</h4>'+
					'Choose Column: &nbsp;&nbsp;<input id="selected_col" name="selected_col" value="col1"/><br/>'+
					'Choose File: &nbsp;&nbsp;<input id="import-user" name="memberlist" style="display: inline-block" type="file" />'+
					'<input id="file_type" name="file_type" value="xls" hidden/>'+
				'</form><br/>';
			$(".back-container").append(form);
		}
		$(function() {
			$("#public-radio").click(function(){
				$(".contest-left").removeClass("col-md-6");
				$("#tmp_form").remove();
				$(".contest-right").remove();
			});
			$("#register-radio").click(function(){
				$(".contest-left").removeClass("col-md-6");
				$("#tmp_form").remove();
				$(".contest-right").remove();

			});
			$("#private-radio").click(function(){
				$(".contest-left").addClass("col-md-6");
				appendRight();
				$("#import-user").change(getList);
			});

		});
	</script>
	<script type="text/javascript">
		var titleData = [];
		$.ajax({
			url: '/ajax/problem_title',
			type: 'GET',
			async: true,
			dataType: 'json',
			success: function(result) {
				titleData = result;
			}
		});
		var count = 0;
		function addProblem() {
			var problemItem = '<div id=p_' + count + '>' +
				'<span>ID </span>' +
				'<div class="search-container">' +
				'<input class="form-control search-title problem-id contest-b-problem-input" type="text" name="problem_id[]" autocomplete="off" />' +
				'<div class="search-option hidden"></div>' +
				'</div>' +
				'<span>Title </span>' +
				'<input class="form-control problem-title contest-b-problem-input" type="text" name="problem_name[]" autocomplete="off" />' +
				'<span>Color</span>' +
				'<input class="form-control" style="width:5%;padding:0;" type="color" name=problem_color[] />' +
				'<a href="javascript:delProblem(' + count + ')">Delete</a>' +
				'</div>';
			$('.back-problem-add-list').append(problemItem);
			count++;
			bindSearchFunction(titleData);
		}
		function delProblem(divId) {
			$('#p_' + divId).remove();
		}
	</script>
</body>
</html>