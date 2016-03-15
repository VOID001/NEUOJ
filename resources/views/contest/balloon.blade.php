@inject('roleCheck', 'App\Http\Controllers\RoleController')
@include('layout.head')
<h1>Contest Balloon</h1>
<div id="">
    <table id="">
        <thead class="">
            <th class="">
                UserName
            </th>
            <th>
                ProblemName
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
                        "<td>" + json[i].contest_problem_id + "</td>" +
                        "<td>" + json[i].username + "</td>" +
                        "<td>" + json[i].event + "</td>" +
                        "</tr>");
            }
        })
    }

    function showBalloon()
    {
        setInterval("getBalloon()", 1000);
    }

    showBalloon();
</script>


