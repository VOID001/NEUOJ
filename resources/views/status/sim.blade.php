<!doctype html>
<head>
    <title>Code Similarity Check</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link href="//cdn.bootcss.com/highlight.js/9.0.0/styles/monokai-sublime.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/js/mergely/mergely.css">
    <link rel="stylesheet" href="/js/mergely/codemirror.css">
    <script src="//cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>
    <script src="//cdn.bootcss.com/highlight.js/9.0.0/languages/cpp.min.js"></script>
    <script type="text/javascript" src="/js/mergely/codemirror.min.js"></script>
    <script type="text/javascript" src="/js/mergely/mergely.min.js"></script>
</head>
<body>
@include("layout.header")
<div>
    <div>Left User:</div>
    <div>Userid: {{ $leftUser->uid }}</div>
    <div>Username: {{ $leftUser->username }}</div>
    <div>Nickname: {{ $leftUser->info->nickname }}</div>
</div>
<div>
    <div>Right User:</div>
    <div>Userid: {{ $rightUser->uid }}</div>
    <div>Username: {{ $rightUser->username }}</div>
    <div>Nickname: {{ $rightUser->info->nickname }}</div>
</div>
@if(isset($sim))
    <div>Similarity <b>{{ $sim->similarity }}%</b></div>
    <div>Sim_Diff</div>
    <div id="compare"></div>
@else
    <div>No sim</div>
    <div id="compare"></div>
@endif
@include("layout.footer")
</body>

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
                editor_height: 1000,
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
    });
</script>