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
        })
        $(document).ready(function(){
            var targetHerf = "/discuss/{{$contest_id}}/p/";
            $("#callBackPager").extendPagination({
                totalPage : {{ $page_num }},
                showPage : 5,
                pageNumber : {{ $page_id }}
            },targetHerf);
        });
    </script>
</head>
<body style="background-color: #ecf0f5">
@include("layout.header")
<div class="panel panel-default discuss_index_pnl">
    <div class="panel-heading">
        @if($contest_id != 0)
            <a href="/contest/{{$contest_id}}">Contest {{$contest_id}} </a>
        @endif
    </div>
    @if(session('info'))
        <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{ session('info') }}</div></div>
    @elseif(count($errors) > 0)
        <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{$errors->all()[0]}}</div></div>
    @endif
    <div class="panel-body">
        <ul class="list-group">
            @if(isset($threads) && $threads != NULL)
                @foreach($threads as $thread)
                    <li class="list-group-item discuss_list_li" onclick="javascript:window.location.href='/discuss/{{$contest_id}}/{{$thread->pid}}'">
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
                            @if($contest_id != 0)
                                <a href="/contest/{{$contest_id}}/problem/{{$thread->pid}}" class="discuss_list_panelbody_a">@Problem {{$thread->pid}}: </a>
                            @else
                                <a href="/problem/{{$thread->pid}}" class="discuss_list_panelbody_a">@Problem {{$thread->pid}}: </a>
                            @endif
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
            @endif
        </ul>
    </div>
    <div id="bottom"></div>
</div>
<div class="text-center" id="callBackPager"></div>
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
