@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
    <title>Source Code {{$runid}}</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link href="//cdn.bootcss.com/highlight.js/9.0.0/styles/monokai-sublime.min.css" rel="stylesheet">
    <script src="//cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>
    <script src="//cdn.bootcss.com/highlight.js/9.0.0/languages/cpp.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script>
        /*$(function(){
            $('pre code').each(function(){
                var lines= $(this).text().split('\n').length;
                var s='<table><tr><td valign="top"><div style="width: 40px;"><code class="cpp">';
                for(var i=1;i<=lines;i++)s+=(i+'<br/>');
                s+='</code></div></td><td valign="top"><div><code class="cpp">'+paste.innerHTML+'</code></div></td></tr></table>';
                paste.innerHTML=s;
            });
        });*/
    </script>
</head>
<body>
@include("layout.header")

<h3 class="text-center">Run ID {{ $runid }}</h3>

<div class="status_main">
    <pre class="source_code">
        <h3>Judge Info</h3>
        @if(!isset($contest))
        <div><a href="/problem/{{ $pid }}">Problem ID :<b>{{ $pid }}</b></a></div>
        @else
        <div><a href="/contest/{{ $contest->contest_id }}/problem/{{ $contestProblemId }}">Problem ID :<b>{{ $contestProblemId }}</b></a></div>
        @endif
        <div>Result: <b>{{ $result }}</b></div>
        <div>JudgeHost: <span class="label label-success">{{ $judgeid }}</span></div>
        <div>Download Source Code</div>
        @if($roleCheck->is('admin'))
            <code class="cpp" id="paste">{{ $code }}</code>
        @elseif($roleCheck->is('able-view-code', ['runid' => $runid]))
            <code class="cpp" id="paste">{{ $code }}</code>
        @endif
        @if($result == "Compile Error" && $err_info != "")
            <h3>Compile/Runtime Error</h3>
            <label>{{ $err_info }}</label>
        @endif
    </pre>
</div>

<div style="height: 50px"></div>
    @include("layout.footer")
</body>
</html>
