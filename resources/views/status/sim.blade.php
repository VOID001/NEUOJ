@include("layout.head")
<link rel="stylesheet" href="/css/main.css">
<link href="//cdn.bootcss.com/highlight.js/9.0.0/styles/monokai-sublime.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>
<script src="//cdn.bootcss.com/highlight.js/9.0.0/languages/cpp.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
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
    <pre class="source_code">
        <code class="cpp" id="paste">{{ $sim_diff }}</code>
    </pre>
@else
    <div>No sim</div>
@endif