@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Contest Ballon</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#contest").addClass("active");
		})
	</script>
</head>
<body>
	@include("layout.header")
	<h3 class="custom-heading">Contest Balloon</h3>
	<div class="front-container">
		<table class="table table-bordered custom-list">
			<thead>
				<th class="text-center">UserName</th>
				<th class="text-center">NickName</th>
				<th>Problem Name</th>
				<th class="text-center">Color</th>
				<th class="text-center">Event</th>
				<th class="text-center">Status</th>
			</thead>
			<tbody id="balloon_list"></tbody>
		</table>
	</div>
	@include("layout.footer")
	<script type="text/javascript">
		function getBalloon() {
			$.ajax({
				url: "/ajax/contest/balloon/",
				type: "GET",
				data: {
					cid: "{{ $contest_id }}"
				},
				dataType: "json",
			})
			.done(function(json){
				$("#balloon_list").html("");
				for(var i = 0; i < json.count; i++) {
					if(json[i].status == "Done") {
						$("#balloon_list").append("<tr>" +
								"<td>" + json[i].username + "</td>" +
								"<td>" + json[i].nickname + "</td>" +
								"<td>" + json[i].short_name + "</td>" +
								"<td>" + "<div style='height:10px; width:100px; background-color:#00" + json[i].color + "00'></div>" + "</td>" +
								"<td>" + json[i].event + "</td>" +
								"<td>" + json[i].status + "</td>" +
								"</tr>");
					}
					else {
						$("#balloon_list").append("<tr>" +
								"<td>" + json[i].username + "</td>" +
								"<td>" + json[i].nickname + "</td>" +
								"<td>" + json[i].short_name + "</td>" +
								"<td>" + "<div style='height:10px; width:100px; background-color:#00" + json[i].color + "00'></div>" + "</td>" +
								"<td>" + json[i].event + "</td>" +
								"<td>" + "<a class='btn btn-default' href = '/contest/{{ $contest_id }}/balloon/" + json[i].id + "'>confirm</a>" + "</td>" +
								"</tr>");
					}
					console.log(json[i]);
				}
			})
		}
		setInterval("getBalloon()", 1000);
	</script>
</body>
</html>