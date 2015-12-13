<meta http-equiv="Refresh" content="2">

<link href="//cdn.bootcss.com/highlight.js/9.0.0/styles/monokai-sublime.min.css" rel="stylesheet">
<script src="//cdn.bootcss.com/highlight.js/9.0.0/highlight.min.js"></script>
<script src="//cdn.bootcss.com/highlight.js/9.0.0/languages/cpp.min.js"></script>
<script>hljs.initHighlightingOnLoad();</script>
<div>
    <h3>
        Run ID {{ $runid }}
    </h3>
    <label><a href="/problem/{{ $pid }}">Problem ID <b>{{ $pid }}</b></a></label>
    <label>Result <b>{{ $result }}</b></label>
    <label>Download Source Code</label>
</div>
<div>
    <h3>Source Code</h3>
<pre>
<code class="cpp">
{{ $code }}
</code>
</pre>
</div>
