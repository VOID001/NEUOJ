<!doctype html>
<html>
<head>
    <title>Edit Contest</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/contest.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_contest").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body class="contest_set_body">
    @include("layout.dashboard_nav")
    @if(time()>strtotime($contest->end_time))
        <script> alert('the contest is out of date');parent.location.href='/dashboard/contest'; </script>
    @endif
    <div class="col-xs-10" >
    <h3 class="text-center">Set Contest</h3>
        @foreach($errors->all() as $error)
            <div>
                <ul>{{ $error }}</ul>
            </div>
        @endforeach
        <form action="/dashboard/contest/{{ $contest->contest_id }}" method="post" class="form-inline" id="contest_set_form">
            {{ csrf_field() }}
            <div class="col-md-offset-4 contest_set_time">
                <div>
                    <label class="col-md-3 contest_set_lable">Contest Name</label>
                    <input type="text" name="contest_name" value="{{ $contest->contest_name }}" class="form-control" required/>
                </div>
                <div>
                    <label class="col-md-3">Begin Time</label>
                    @if(time()<strtotime($contest->begin_time))
                    <input type="datetime-local" name="begin_time" value="{{ str_replace(" ", "T", $contest->begin_time) }}" required/>
                    @else
                    {{$contest->begin_time}}
                    <input type="hidden" name = "begin_time" value="{{ $contest->begin_time }}" class="form-control"/>
                    @endif
                </div>
                <div>
                    <label class="col-md-3 contest_set_lable">End Time</label>
                    <input type="datetime-local" name="end_time" value="{{ str_replace(" ", "T", $contest->end_time) }}" class="form-control" required/>
                </div>
                <div>
                    <label class="col-md-3" >Contest Type</label>
                    <input type="radio" name="contest_type" value="public" id="hideList"
                    @if($contest->contest_type == 0) 
                    checked 
                    @endif
                    />public
                    <input type="radio" name="contest_type" value="private" id="showList"
                    @if($contest->contest_type == 1) 
                    checked
                    @endif 
                    />private

                    <input type="radio" name="contest_type" value="register" id="contest_set_register_rdo"

                    @if($contest->contest_type == 2)
                    checked
                    @endif
                    />register
                </div>
                <div id="contest_set_register_list">
                <div >
                    <label class="col-md-3 contest_set_lable">Register Begin Time</label>
                    <input type="datetime-local" name="register_begin_time" value="{{ str_replace(" ", "T", $contest->register_begin_time) }}" class="form-control" 
                    @if($contest->contest_type == 2)
                    required
                    @endif
                    />
                </div>
                <div>
                    <label class="col-md-3 contest_set_lable">Register End Time</label>
                    <input type="datetime-local" name="register_end_time" value="{{ str_replace(" ", "T", $contest->register_end_time) }}" class="form-control"
                    @if($contest->contest_type == 2)
                    required
                    @endif
                    />
                </div>
                </div>
            </div>
            <div class="clearfix"></div>
            <div id="allowedUserList" class="col-md-offset-4 contest_set_allowedUserList">
                <div>
                    <label class="col-md-3">Import user list from file</label>
                    <input type="file"  name="user_list" />
                </div>
                <div class="clearfix"></div>
                <div>
                    <label class="col-md-3">Input Allowed User name</label>
                    <div>
                        <textarea name="user_list" placeholder="Input the user name , seperate each with comma" class="form-control">
                        @if(isset($contestUser))
                            @for($i = 0;$i < count($contestUser); $i++)
                                {{$contestUser[$i]['username']}}
                                @if($i!=count($contestUser)-1)
                                ,
                                @endif
                            @endfor
                        @endif
                        </textarea>
                    </div>
                    <div id="add_user_list">
                        <!-- ID map to username -->
                    </div>
                </div>
            </div>

            <div id="add_problem" class="contest_set_add_problem">
                <label class="col-md-offset-5">Select Problem</label>
                <a href="javascript:addProblem()">Add Problem</a>
                @for($i = 0; $i < $problem_count; $i++)
                    <div id="p_{{ $i }}">
                        <label class='col-md-offset-2'>Problem ID</label>
                        <input type="text" name="problem_id[]" value="{{ $contestProblem[$i]['problem_id'] }}" readonly="true" class="form-control"/>
                        <label>Problem Title In Contest</label>
                        <input type="text" name="problem_name[]" value="{{ $contestProblem[$i]['problem_title'] }}" readonly="true" class="form-control"/>
                        @if(time()<strtotime($contest->begin_time))
                        <a href="javascript:delProblem({{ $i }})">Delete Problem</a>
                        @endif
                    </div>
                @endfor
                
            </div>
            <div class="clearfix"></div>
            <div class="text-center">
                <input type="submit" value="Submit"/>
            </div>
        </form>
    </div>
    <script language="javascript">

        var count = {{$problem_count}};
        function addProblem()
        {
            var problemItem = "<div class='col-md-offset-2'  id=p_" + count +" >\n" +
                    "<label>Problem ID</label>\n" +
                    "<input class='form-control' type='text' name=problem_id[]/>\n" +
                    "<label>Problem Title In Contest</label>\n" +
                    "<input class='form-control' type='text' name=problem_name[]/>\n" +
                    "<label>Color</label>\n" +
                    "<input class='form-control' style='width:5%;padding:0;' type='color' name=problem_color[]/>\n" +
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

            if($('#contest_set_register_rdo')[0].checked) {
                $('#contest_set_register_list').show();
            } else {
                $('#contest_set_register_list').hide();
            }

            $('#showList').click(function(){
                $('#allowedUserList').slideDown('slow');
                $('#contest_set_register_list').slideUp('slow');
            })
            $('#hideList').click(function(){
                $('#allowedUserList').slideUp('slow');
                $('#contest_set_register_list').slideUp('slow');
            })
            $('#contest_set_register_rdo').click(function(){
                $('#allowedUserList').slideUp('slow');
                $('#contest_set_register_list').slideDown('slow');
            })
        })
    </script>
</body>
</html>