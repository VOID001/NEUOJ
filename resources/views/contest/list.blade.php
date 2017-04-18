@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Contest List</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/extendPagination.js"></script>
	<script type="text/javascript">
		$(function() {
			$("#contest").addClass("active");
			var targetHerf = "/contest/p/";
			$('#callBackPager').extendPagination({
				totalPage : {{ $page_num }},
				showPage : 5,
				pageNumber : {{ $page_id }}
			},targetHerf);
		})
	</script>
</head>
<body class="body-scroll-y">
	@include("layout.header")
	<h3 class="custom-heading">Contest List</h3>
	<div class="front-container">
		<table class="table table-striped table-bordered custom-list">
			<thead>
				<th class="text-center" width="12%">Contest ID</th>
				<th class="text-center" width="20%">Contest Title</th>
				<th class="text-center" width="18%">Start Time</th>
				<th class="text-center" width="8%">Type</th>
				<th class="text-center" width="10%">Status</th>
				<th class="text-center" width="10%">Ranklist</th>
				@if($roleCheck->is('admin'))
					<th class="text-center" width="12%">Management</th>
				@endif
			</thead>
			@if(isset($contests))
				@foreach($contests as $contest)
					<tr class="front-table-row">
						<td>
							<paper-button><a href="/contest/{{ $contest->contest_id }}">{{ $contest->contest_id }}</a></paper-button>
						</td>
						<td>
							<paper-button><a class="custom-word" href="/contest/{{ $contest->contest_id }}">&nbsp;{{ $contest->contest_name }}</a></paper-button>
						</td>
						<td>
							<paper-button><a href="/contest/{{ $contest->contest_id }}">{{ $contest->begin_time }}</a></paper-button>
						</td>
						<td>
							@if($contest->contest_type == 0)
								Public
							@elseif($contest->contest_type == 1)
								Private
							@elseif($contest->contest_type == 2)
								Register
							@endif
						</td>
						<td>
							@if($contest->status=="Running")
								<span class="label label-info">{{ $contest->status }}</span>
							@elseif($contest->status=="Ended")
								<span class="label label-default">{{ $contest->status }}</span>
							@else
								<span class="label label-default">{{ $contest->status }}</span>
							@endif
						</td>
						<td>
							<button class="btn btn-default" onClick="window.location.href='/contest/{{ $contest->contest_id }}/ranklist';">Watch</button>
						</td>
						@if($roleCheck->is('admin'))
							<td>
								<button class="btn btn-default" onClick="window.location.href='/dashboard/contest/{{ $contest->contest_id }}';">MANAGE</button>
							</td>
						@endif
					</tr>
				@endforeach
			@endif
		</table>
		<div class="text-center" id="callBackPager"></div>
	</div>
	@include("layout.footer")
</body>
</html>
