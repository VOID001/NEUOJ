@inject('roleCheck', 'App\Http\Controllers\RoleController')
@include('layout.head')
<!doctype html>
<html>
<head>
    <title>Contest Ballon</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/contest.css">
    <script type="text/javascript">
        $(function(){
            $("#contest").addClass("active");
        })
    </script>
</head>
<body>
    @include("layout.header")
    <h3 class="text-center">Contest Balloon</h3>
    <div class="main" >
        <table class="table table-striped table-bordered table-hover contest_list contest_table" width="100%" >
            <thead>
                <th class="">
                    UserName
                </th>
                <th>
                    Problem Name
                </th>
                <th>
                    Color
                </th>
                <th>
                    Event
                </th>
            </thead>
            <tbody id="balloon_list">

            </tbody>
        </table>
    </div>

<script type="text/javascript">
    function getBalloon() {
        $.ajax({
            url: "/ajax/contest/balloon/",
            type: "GET",
            data: {
                cid: "{{ $contest_id }}"
            },
            dataType: "json",
        })

        .done(function(json){
            $("#balloon_list").html("");
            for(var i = 0; i < json.count; i++) {
                $("#balloon_list").append("<tr>" +
                        "<td>" + json[i].username + "</td>" +
                        "<td>" + json[i].short_name + "</td>" +
                        "<td>" + "<div style='height:10px; width:100px; background-color:#00" + json[i].color + "00'></div>" + "</td>" +
                        "<td>" + json[i].event + "</td>" +
                        "</tr>");
                console.log(json[i]);
            }
        })
    }

    function showBalloon()
    {
        setInterval("getBalloon()", 1000);
    }

    showBalloon();
</script>
<div style="padding-bottom: 40px">
@include("layout.footer")
</body>
</html>

