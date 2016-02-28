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
        <h3>Source Code</h3>
        <label><a href="/problem/{{ $pid }}">Problem ID :<b>{{ $pid }}</b></a></label>
        <label>Result :<b>{{ $result }}</b></label>
        <label>Download Source Code</label>
        <code class="cpp" id="paste">{{ $code }}</code>
    </pre>
</div>

<div style="height: 50px"></div>
    @include("layout.footer")
</body>
</html>
