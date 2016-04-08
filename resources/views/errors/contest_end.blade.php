<!doctype html>
<html>
<head>
    <title>Contest Ended!</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    @include("layout.header")
    <h1 class="text-center">QWQ</h1>
    <h1 class="text-center">Contest Ended!</h1>

    <div align="center"><img src="/image/cry.jpg" height="50%"/></div>

    <div class="text-center">
        You are not allowed to submit now<br/>
        Click <a href="/contest/{{ $contest->contest_id }}">Here</a> to back to contest Page
    </div>
    <br/>
    <br/>
    @include("layout.footer")
</body>
</html>