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
<h3 class="text-center">Discuss {{$contest_id}} {{$problem_id}}</h3>
<div class="main">
    <table class="table table-striped table-bordered table-hover problem_table" id="threadlist" width="100%">
        @if(session('info'))
            <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{ session('info') }}</div></div>
        @elseif(count($errors) > 0)
            <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{$errors->all()[0]}}</div></div>
        @endif
        <thead>
        <tr>
            <th class="text-center" id="thread_id">Thread ID</th>
            <th class="text-left" id="author_id">Author ID</th>
            <th class="text-center" id="thread_content">Content</th>
            <th class="text-center" id="created_at">Time</th>
            @if($roleCheck->is("admin"))
            <th class="text-center" id="delete">Delete</th>
            @endif
            {{--<th class="text-center">Visibility_Lock(use for debug version)</th>--}}
        </tr>
        </thead>
        @if(isset($threads) && $threads != NULL)
            @foreach($threads as $thread)
                <tr class="table_row">
                    <th class="text-center" id="thread_id">{{$thread->id}}</th>
                    <th class="text-left" id="author_id">{{$thread->author_id}}</th>
                    <th class="text-center" id="thread_content">{{$thread->content}}</th>
                    <th class="text-center" id="thread_content">{{$thread->created_at}}</th>
                    @if($roleCheck->is("admin"))
                    <th>
                        <form action="/discuss/delete/{{$thread->id}}" method ="POST">
                            {{ csrf_field() }}
                            <input type="submit" value="delete"/>
                        </form>
                    </th>
                    @endif
                </tr>
            @endforeach
        @endif
    </table>
    <form action="/discuss/add/{{ $contest_id }}/{{ $problem_id }}" method ="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="text" name="content"/>
        <input type="submit" value="add"/>
    </form>
</div>
@include("layout.footer")
</body>
</html>
