@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
	<title>Discuss {{$contest_id}} {{$problem_id}}</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
</head>
<body>
	@include("layout.header")
	<div class="front-container">
		<div class="panel panel-default">
			<div class="panel-heading discuss-front-panel-heading">
				@if($contest_id != 0)
					<a href="/discuss/{{$contest_id}}/p/1">Contest {{$contest_id}} </a> >
					<a href="/contest/{{$contest_id}}/problem/{{$problem_id}}">Problem {{$problem_id}} </a>
				@else
					<a href="/problem/{{ $problem_id }}">Problem {{$problem_id}} </a>
				@endif
			</div>
			<div class="panel-body">
				<ul class="list-group">
					@if(isset($threads) && $threads != NULL)
						@foreach($threads as $thread)
							<li class="list-group-item discuss-front-li">
								<div class="text-center discuss-front-information" onclick="window.location.href='/profile/{{ $thread->author_id }}'">
										<a href="/profile/{{ $thread->author_id }}"><img class="img-circle" src="/avatar/{{ $thread->author_id }}" /></a>
									<br/>
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
								<div class="discuss-front-content discuss-front-content-bubble">
										<p>{{$thread->content}}</p>
									<span class="pull-right text-muted"><a onclick="replyTo({{ $thread->id }})">#{{ $thread->id }}</a> at {{$thread->created_at}}</span>
								</div>
							</li>
						@endforeach
					@else
						<li class="list-group-item">
							还没有人回复呢, 快来抢沙发0.0
						</li>
					@endif
				</ul>
			</div>
			<div class="panel-footer">
				<form id="discuss-index-footer" action="/discuss/add/{{ $contest_id }}/{{ $problem_id }}" method ="POST" enctype="multipart/form-data">
					{{ csrf_field() }}
					<textarea id="replybox" type="text" class="form-control" rows="3" name="content" placeholder="在这里输入你想说的0.0"/></textarea>
					@if(session('info'))
						<div class="label label-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{ session('info') }}</div>
					@elseif(count($errors) > 0)
						<div class="label label-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{$errors->all()[0]}}</div>
					@endif
					<input type="submit" class="btn-success pull-right btn" value="发出去了喵~" style=""/>
				</form>
			</div>
		</div>
	</div>
	@include("layout.footer")
	<script type="text/javascript">
		$('.discuss-front-btn').click(function(event) {
			event.stopPropagation();
		})
		function replyTo(thread_id)
		{
			var content = $('#replybox').val();
			content = ">> No." + thread_id + "\r\n" + content;
			var doc_height = $(document).height();
			var scroll_top = $(document).scrollTop();
			var window_height = $(window).height();
			/* If not at the bottom of the page, then scroll to the bottom to reply */
			if(scroll_top + window_height < doc_height)
			{
				$("html,body").animate({scrollTop: $(document).height()}, 1000);
			}
			$('#replybox').val(content);
			$('#replybox').focus();
		}
	</script>
</body>
</html>
