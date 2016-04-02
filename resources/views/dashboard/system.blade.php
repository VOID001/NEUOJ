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
@include("layout.dashboard_nav")
<div class="col-xs-10 padding_10">
    {!! phpinfo() !!}
    <div style="height: 192px"></div>
</div>
</body>
</html>
