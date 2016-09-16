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
    <style>
        table td,
        table th{
            padding: 5px;
        }
        #running_font {
            font-family: Helvetica, 'Hiragino Sans GB', 'Microsoft Yahei', '微软雅黑', Arial, sans-serif;
        }
    </style>
</head>
<body>
@include("layout.header")

<h3 class="text-center">Run ID {{ $runid }}</h3>

<div class="status_main">
        <h3>Source Code</h3>
        @if(!isset($contest))
            <div><a href="/problem/{{ $pid }}">Problem ID :<b>{{ $pid }}</b></a></div>
        @else
            <div><a href="/contest/{{ $contest->contest_id }}/problem/{{ $contestProblemId }}">Problem ID :<b>{{ $contestProblemId }}</b></a></div>
        @endif
    <div>Result: <b>{{ $result }}</b></div>
    <div>Download Source Code</div>
    <pre class="source_code">
        <code class="cpp" id="paste">{{ $code }}</code>
    </pre>
    <br/>
    @if($result == "Compile Error" && $err_info != "")
        <h3>Compile Error</h3>
        <label>{{ $err_info }}</label>
        <br/>
    @else
    <div>
        <h3>Runnings</h3>

        <div class="panel-group" id="accordion">
            @foreach($runnings as $running)
            <div class="panel panel-default text-muted">
                <div class="panel-heading">
                    <a data-toggle="collapse" href="#collapse{{$running->testcase_rank_id}}">Testcase {{$running->testcase_rank_id}} (Score:{{$running->testcase_data->score}})
                        @if($running->result == "Accepted")
                            <label class="label label-success pull-right">{{ $running->result }}</label>
                        @elseif($running->result == "Wrong Answer")
                            <label class="label label-danger pull-right">{{ $running->result }}</label>
                        @else
                            <label class="label label-warning pull-right">{{ $running->result }}</label>
                        @endif
                    </a>
                </div>
                <div id="collapse{{$running->testcase_rank_id}}" class="panel-collapse collapse">
                    <div class="panel-body" id="running_font">
                        <b>Exec_Time:</b>
                        @if($running->exec_time < 1)
                            {{ (int)($running->exec_time * 1000) }}ms
                        @else
                            {{ $running->exec_time }}s
                        @endif
                        <br/>
                        <b>Exec_mem:</b>
                        @if($running->exec_mem < 1024)
                            {{ $running->exec_mem }} Byte
                        @elseif($running->exec_mem < 1024*1024)
                            {{ (int)($running->exec_mem / 1024) }} KB
                        @else
                            {{ (int)($running->exec_mem / 1024 / 1024) }} MB
                        @endif
                        <br/>
                        <b>Language:</b> {{$running->lang}}<br/>
                        @if($roleCheck->is("admin"))
                            <b>Input File:</b> <a href="/storage/testdata?file={{ $running->testcase_data->input_file_name }}">{{ $running->testcase_data->input_file_name }} </a><br/>
                            <b>Output File:</b> <a href="/storage/testdata?file={{ $running->testcase_data->output_file_name }}">{{ $running->testcase_data->output_file_name }} </a><br/>
                            <b>Judge Message:</b><br/>
                            <pre><code>{{$running->output_diff}}</code></pre>
                        @endif

                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

<div style="height: 50px"></div>
    @include("layout.footer")
</body>
</html>
