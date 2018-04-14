<!doctype html>
<html>
<head>
    <title>Register</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/contest.css">
    <script src="/js/extendPagination.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#contest").addClass("active");
        })
    </script>
</head>
<body class="home_body">
@include("layout.header")
<h3 class="text-center">Register Contest</h3>
@if (count($errors) > 0)
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
    <div id="contest_register_picture">
    <form action = "/contest/{{$contest_id}}/register" method = "post">
        {{csrf_field()}}
        <div class="panel panel-default">
            <div class="panel-body">
                <input type="text" id="contest_register_picture_text" name="captcha" class="form-control"  tabindex="3" placeholder="please input captcha"/>
                <img src="{{ captcha_src("flat") }}" alt="Captcha" id="captcha"/>
            </div>
            <div class="panel-footer">
                <input class="btn btn-info contest_register_btn" type = "submit" value = "register"/>
                <input class="btn btn-danger contest_register_btn" onclick="javascript:window.location.href='/contest/p/1'" value = "cancel"/>
            </div>
        </div>
    </form>
    </div>
</body>
</html>