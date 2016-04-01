<!doctype html>
<head>
    <title>Code Similarity Check</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link href="//cdn.bootcss.com/highlight.js/9.0.0/styles/monokai-sublime.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/status.css">
    <link rel="stylesheet" href="/js/mergely/mergely.css">
    <link rel="stylesheet" href="/js/mergely/codemirror.css">
    <script src="//cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>
    <script src="//cdn.bootcss.com/highlight.js/9.0.0/languages/cpp.min.js"></script>
    <script type="text/javascript" src="/js/mergely/codemirror.min.js"></script>
    <script type="text/javascript" src="/js/mergely/mergely.min.js"></script>
</head>
<body>
@include("layout.header")
<h3 class="text-center">Code Similarity Check
    @if(isset($sim))
        <span class="label label-danger " style="font-size: 12px">Similarity <b>{{ $sim->similarity }}%</b></span>
    @else
        <span class="label label-success" style="font-size: 12px">No sim</span>
    @endif
</h3>
<br>
<div class="panel panel-danger status_sim_pnl">
    <div class="panel-heading">Userid: {{ $leftUser->uid }}</div>
    <div class="panel-body">
      <div>Username: {{ $leftUser->username }}</div>
      <div>Nickname: {{ $leftUser->info->nickname }}</div>
      <div>学号: {{ $leftUser->info->stu_id }}</div>
      <div>真实姓名: {{ $leftUser->info->realname }}</div>
    </div>
</div>
<div class="panel panel-info status_sim_pnl" id="status_sim_rpnl">
    <div class="panel-heading">Userid: {{ $rightUser->uid }}</div>
    <div class="panel-body">
        <div>Username: {{ $rightUser->username }}</div>
        <div>Nickname: {{ $rightUser->info->nickname }}</div>
        <div>学号: {{ $rightUser->info->stu_id }}</div>
        <div>真实姓名: {{ $rightUser->info->realname }}</div>
    </div>
</div>

@if(isset($sim))
    <div id="compare"></div>
@else
    <div id="compare"></div>
@endif
<div style="height: 350px"></div>
@include("layout.footer")
<script type="text/javascript">
    /* This function is used for create a multi-line string(code segment) */
    function multi_string_init(str)
    {
        return str.toString().split('\n').slice(1, -1).join('\n') + '\n';
    }

    /* the comment in the function will be converted into multi-line code */
    var lcode = multi_string_init(function(){/*{!! $lcode !!}}*/});
    var rcode = multi_string_init(function(){/*{!! $rcode !!}}*/});
    console.log(lcode);
    $(document).ready(function () {
        $('#compare').mergely({
            cmsettings: {
                readOnly: true,
                lineNumbers: true,
                editor_height: 300,
                fadein: true,
                sidebar: false,
                viewport: true,
            },
            lhs: function(setValue) {
                setValue(lcode);
            },
            rhs: function(setValue) {
                setValue(rcode);
            }
        });
        $("#compare-editor-lhs").css("width","45%");
        $("#compare-editor-lhs").css("height","300px");
        $("#compare-editor-rhs").css("width","45%");
        $("#compare-editor-rhs").css("height","300px");
        $(".mergely-canvas").css("height","300px");
        $(".mergely-margin").css("height","300px");
    });
</script>
</body>
</html>

