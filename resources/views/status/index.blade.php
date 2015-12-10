

<link rel="stylesheet" href="/hljs/src/styles/monokai.css">
<script src="/hljs/src/highlight.js"></script>
<script>hljs.initHightlingOnLoad();</script>
<div>
    <h3>
        Run ID {{ $runid }}
    </h3>
    <label>Problem ID <b>{{ $pid }}</b></label>
    <label>Result <b>{{ $result }}</b></label>
    <label>Download Source Code</label>
</div>
<div>
    <h3>Source Code</h3>
    <pre>
        <code class="c">
    {!! $code !!}
        </code>
    </pre>
</div>
