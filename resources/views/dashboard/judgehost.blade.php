<!doctype html>
<html>
<head>
    <title>Judgehost Manage</title>
    @include("layout.head")
    @include("layout.dashboard_header")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript" src="/js/Chart.min.js"></script>
    <script type="text/javascript">
        $(function() {
            $("#dashboard_profile").addClass("dashboard-subnav-active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="back-container">
    <h3 class="custom-heading">Judgehost Manage</h3>
    <div id="judge_stat">
        <table id="judgehost_stat" class="table table-bordered table-hover">
            <thead>
                <td>JudgeHost</td>
                <td>Status</td>
                <td>Restart</td>
                <td>Stop</td>
                <td>Cleanup</td>
            </thead>
        </table>
    </div>
</div>
<script type="text/javascript">
    function getAjax() {
        $.ajax({
            url: '/ajax/judgehost_status',
            type: 'GET',
            async: true,
            dataType: 'json',
            success: function (data) {
                $("#judgehost_stat").empty();
                console.log(data);
                for (var key in data) {
                    var str = "<tr><td>" + key + "</td><td>" + data[key] + "</td>";
                    var btnStart = "<td><button id='" + key + "' onClick='startJudge()'>Start</button></td>";
                    var btnStop = "<td><button id='" + key + "' onClick='stopJudge()'>Stop</button></td>";
                    var btnClean = "<td><button id='" + key + "' onClick='cleanJudge()'>CleanUp</button></td></tr>";
                    $("#judgehost_stat").append(str + btnStart + btnStop + btnClean);
                }
            }
        });
    }
    getAjax();
    setInterval(getAjax, 2000);

    function startJudge()
    {
        $.ajax({
            url: '/ajax/judgehost_start',
            type: 'POST',
            async: true,
            dataType: 'json',
            success: function(data){
                console.log(data);
            }
        })
    }

    function stopJudge()
    {
        $.ajax({
            url: '/ajax/judgehost_stop',
            type: 'POST',
            async: true,
            dataType: 'json',
            success: function(data){
                console.log(data);
            }
        })
    }

    function cleanJudge()
    {

        $.ajax({
            url: '/ajax/judgehost_clean',
            type: 'POST',
            async: true,
            dataType: 'json',
            success: function (data) {
                console.log(data);
            }
        });
    }

</script>
</body>
</html>
