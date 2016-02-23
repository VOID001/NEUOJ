<!doctype html>
<html>
<head>
    <title>Profile</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_contest").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body class="contest_set_body">
@include("layout.dashboard_nav")
<div class="col-xs-10">

    <h3 class="text-center">Add Contest</h3>
    @foreach($errors->all() as $error)
        <div>
            <ul>{{ $error }}</ul>
        </div>
    @endforeach
    <form id="contest_add_form" action="/dashboard/contest/add" class="form-inline" method="post">
        {{ csrf_field() }}
        <div class="col-md-offset-4 contest_set_time">
            <div >
                <label class="col-md-3 contest_set_lable" >Contest Name</label>
                <input type="text" name="contest_name" value="{{ old('contest_name') }}" class="form-control" required/>
            </div>
            <div>
                <label class="col-md-3 contest_set_lable">Begin Time</label>
                <input type="datetime-local" name="begin_time" value="{{ old('begin_time') }}" class="form-control" required/>
            </div>
            <div>
                <label class="col-md-3 contest_set_lable">End Time</label>
                <input type="datetime-local" name="end_time" value="{{ old('end_time') }}" class="form-control" required/>
            </div>
            <div>
                <label class="col-md-3">Contest Type</label>
                <input type="radio" name="contest_type" value="public"  id="hideList" checked/><label>public</label>
                <input type="radio" name="contest_type" value="private" id="showList"/><label>private</label>
            </div>
        </div>
        <div id="allowedUserList" class="col-md-offset-4 contest_set_allowedUserList">
            <div >
                <label class="col-md-3">Import user list from file</label>
                <input type="file" name="user_list"/>
            </div>
            <div class="clearfix"></div>
            <div>
                <label class="col-md-3">Input Allowed User name</label>
                <div>
                    <textarea class="form-control" name="user_list" placeholder="Input the user name , seperate each with comma"></textarea>
                </div>
                <div id="add_user_list">
                    <!-- ID map to username -->
                </div>
            </div>
        </div>
        <!-- This part is used to add problem , now we use Blade php to add problems -->
        <div id="add_problem" class="contest_set_add_problem">
            <label class="col-md-offset-5">Select Problem</label>
            <a href="javascript:addProblem()">Add Problem</a>
            <div class="clearfix"></div>

            @if(session('problem_count'))
                @for($i = 0, $problem = session('current_problem'); $i < session('problem_count'); $i++)
                    <div id="p_{{ $i }}" >
                        <label class='col-md-offset-2'>Problem ID</label>
                        <input class="form-control" type="text" name="problem_id[]" value="{{ $problem[$i]['problem_id'] }}"/>
                        <label>Problem Title In Contest</label>
                        <input class="form-control" type="text" name="problem_name[]" value="{{ $problem[$i]['problem_name'] }}"/>
                        <a href="javascript:delProblem({{ $i }})">Delete Problem</a>
                    </div>
                @endfor
            @endif
            <!-- How to make it work -->
        </div>
        <!-- This part is used to add problem , now we use Blade php to add problems -->
        <div class="text-center">
            <input type="submit" value="Submit"/>
        </div>
    </form>
</div>
    <script language="javascript">
      //      hideAllowedUserList();
        var count = 0;
        function addProblem()
        {
            var problemItem = "<div class='col-md-offset-2' id=p_" + count +" >\n" +
                    "<label>Problem ID</label>\n" +
                    "<input class='form-control' type='text' name=problem_id[]/>\n" +
                    "<label>Problem Title In Contest</label>\n" +
                    "<input class='form-control' type='text' name=problem_name[]/>\n" +
                    "<a href='javascript:delProblem(" + count + ")'>Delete Problem</a>\n"  +
                    "</div>";
            document.getElementById("add_problem").insertAdjacentHTML("beforeEnd",problemItem);
            count++;

        }

        function delProblem(div_id)
        {
            document.getElementById("add_problem").removeChild(document.getElementById("p_" + div_id));
        }

        $(document).ready(function(){
            var list_checked = $('#showList')[0].checked;
            if(list_checked){
                $('#allowedUserList').show();
            }
            else{
                $('#allowedUserList').hide();
            }
            $('#showList').click(function(){
                $('#allowedUserList').slideDown('slow');
            })
            $('#hideList').click(function(){
                $('#allowedUserList').slideUp('slow');
            })
        })
    </script>
</body>
</html>