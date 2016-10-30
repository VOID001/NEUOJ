@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Discuss {{$contest_id}}</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script src="/js/extendPagination.js"></script>
	<script type="text/javascript">
		$(function(){
			$("#discuss").addClass("active");
			var targetHerf = "/discuss/{{$contest_id}}/p/";
			$("#callBackPager").extendPagination({
				totalPage : {{ $page_num }},
				showPage : 5,
				pageNumber : {{ $page_id }}
			},targetHerf);
		})
	</script>
</head>
<body>
	@include("layout.header")
	<div class="front-container">
		<div class="panel panel-default">
			<div class="panel-heading discuss-front-panel-heading">
				@if($contest_id != 0)
					<a href="/contest/{{$contest_id}}">Contest {{$contest_id}} </a>
				@endif
			</div>
			@if(session('info'))
				<div class="label label-warning">{{ session('info') }}</div>
			@elseif(count($errors) > 0)
				<div class="label label-warning">{{$errors->all()[0]}}</div>
			@endif
			<div class="panel-body">
				<ul class="list-group">
					@if(isset($threads) && $threads != NULL)
						@foreach($threads as $thread)
							<li class="list-group-item discuss-front-li">
								<div class="text-center discuss-front-information" onclick="window.location.href='/profile/{{ $thread->author_id }}'">
										<img class="img-circle" src="/avatar/{{ $thread->author_id }}" />
									@if($thread->author_id <= 2)
										<b class="discuss-front-blue-word custom-word center-block">
											<span class="glyphicon glyphicon-fire"></span>
											&nbsp;{{ $thread->info->nickname }}
										</b>
									@else
										<span class="discuss-front-green-word custom-word center-block">{{ $thread->info->nickname }}</span>
									@endif
									@if($roleCheck->is("admin"))
										<span class="custom-word center-block">({{ $thread->info->realname }})</span>
										<form action="/discuss/delete/{{$thread->id}}" method ="POST">
											{{csrf_field()}}
											<input class="discuss-front-btn center-block btn btn-default" type="submit" value="delete"/>
										</form>
									@endif
								</div>
								<div class="discuss-front-content discuss-front-content-bubble" onclick="window.location.href='/discuss/{{$contest_id}}/{{$thread->pid}}'">
									@if($contest_id != 0)
										<a href="/contest/{{$contest_id}}/problem/{{$thread->pid}}">@Problem {{$thread->pid}}: </a>
									@else
										<a href="/problem/{{$thread->pid}}">@Problem {{$thread->pid}}: </a>
									@endif
									<p>{{$thread->content}}</p>
									<span class="pull-right text-muted"><a>#{{ $thread->id }}</a> at {{$thread->created_at}}</span>
								</div>
							</li>
						@endforeach
					@else
					@endif
				</ul>
			</div>
		</div>
		<div class="text-center" id="callBackPager"></div>
	</div>
	@include("layout.footer")
</body>
</html>