<!doctype html>
<html>
<head>
    <title>System Info</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_settings").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
    {!! phpinfo() !!}
</body>
</html>
