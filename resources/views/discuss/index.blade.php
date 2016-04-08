@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
    <title>Discuss {{$contest_id}} {{$problem_id}}</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
</head>
<body style="background-color: #ecf0f5">
@include("layout.header")
<div class="panel panel-default discuss_index_pnl">
    <div class="panel-heading">
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
                    <li class="list-group-item">
                        <div class="col-md-2 text-center">
                                <a href="/profile/{{ $thread->author_id }}"><img src="/avatar/{{ $thread->author_id }}" class="img-circle" style="width: 50px;height: 50px"></a>
                            <br/>
                            @if($thread->author_id <= 2)
                                <span><a class="admin_href" href="/profile/{{ $thread->author_id }}"><b>{{ $thread->info->nickname }}</b>@if($roleCheck->is("admin"))<br/>({{ $thread->info->realname }})@endif</a></span>
                            @else
                                <span><a href="/profile/{{ $thread->author_id }}">{{ $thread->info->nickname }}@if($roleCheck->is("admin"))<br/>({{ $thread->info->realname }})@endif</a></span>
                            @endif
                        </div>
                        <div class="col-md-10">
                            <p style="white-space: pre-wrap">{{$thread->content}}</p>
                        </div>
                        @if($roleCheck->is("admin"))
                            <form class="col-md-2" action="/discuss/delete/{{$thread->id}}" method ="POST">
                                {{csrf_field()}}
                                <input type="submit" class="form-control btn btn-default discuss_index_delete_btn" value="delete"/>
                            </form>
                        @endif
                        <span style="float: left;font-size: 12px;color: #9d9d9d;" class="pull-right"><a style="cursor: pointer" onclick="replyTo({{ $thread->id }})">#{{ $thread->id }}</a> at {{$thread->created_at}}</span>
                    </li>
                @endforeach
            @else
                <li class="list-group-item">
                    还没有人回复呢, 快来抢沙发0.0
                </li>
            @endif
        </ul>
    </div>
    <div id="bottom"></div>
    <div class="panel-footer">
        <form action="/discuss/add/{{ $contest_id }}/{{ $problem_id }}" method ="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <textarea id="replybox" type="text" class="form-control" rows="3" name="content" placeholder="在这里输入你想说的0.0"/></textarea>
            <div style="height: 3px"></div>
            @if(session('info'))
                <div class="form-group"><div class="label label-danger" style="font-size: 12px"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{ session('info') }}</div></div>
            @elseif(count($errors) > 0)
                <div class="form-group"><div class="label label-danger" style="font-size: 12px"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;{{$errors->all()[0]}}</div></div>
            @endif
            <input type="submit" class="form-control btn-success pull-right" value="发出去了喵~" style="width: 15%;margin-top: 1%"/>
        </form>
    </div>
</div>
<div style="height: 50px"></div>
@include("layout.footer")

<script type="text/javascript">
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
