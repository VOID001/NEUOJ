<!doctype html>
<html>
<head>
</head>
<body>
@if(time()>strtotime($contest->end_time))
    <script> alert('the contest is out of date');parent.location.href='/dashboard/contest'; </script>
@endif
<h1>Set Contest</h1>
    @foreach($errors->all() as $error)
        <div>
            <ul>{{ $error }}</ul>
        </div>
    @endforeach
    <form action="/dashboard/contest/{{ $contest->contest_id }}" method="post">
        {{ csrf_field() }}
        <label>Contest Name</label>
        <input type="text" name="contest_name" value="{{ $contest->contest_name }}"required/>
        <br>
        <label>Begin Time</label>
        @if(time()<strtotime($contest->begin_time))
        <input type="datetime-local" name="begin_time" value="{{str_replace(" ", "T", $contest->begin_time)}}"required/>
        <br>
        @else
        {{$contest->begin_time}}
        <input type="hidden" name = "begin_time" value={{$contest->begin_time}}/>
        <br>
        @endif
        <label>End Time</label>
        <input type="datetime-local" name="end_time" value="{{str_replace(" ", "T", $contest->end_time)}}"required/>
        <br>
        <label>Contest Type</label>
        <input type="radio" name="contest_type" value="public" onclick="hideAllowedUserList()" 
        @if($contest->contest_type == 0) 
        checked 
        @endif
        />public
        <input type="radio" name="contest_type" value="private" onclick="showAllowedUserList()"
        @if($contest->contest_type == 1) 
        checked
        @endif 
        />private
        <br>
        <div id="allowedUserList">
            <div>
                <label>Import user list from file</label>
                <input type="file" name="user_list"/>
            </div>
            <div>
                <label>Input Allowed User name</label>
                <div>
                    <textarea name="user_list" placeholder="Input the user name , seperate each with comma">
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

        <div id="add_problem">
            <label>Select Problem</label>
            <a href="javascript:addProblem()">Add Problem</a>
            @for($i = 0; $i < $problem_count; $i++)
                <div id="p_{{ $i }}">
                    <label>Problem ID</label>
                    <input type="text" name="problem_id[]" value="{{ $contestProblem[$i]['problem_id'] }}" readonly="true" />
                    <label>Problem Title In Contest</label>
                    <input type="text" name="problem_name[]" value="{{ $contestProblem[$i]['problem_title'] }}" readonly="true" />
                    @if(time()<strtotime($contest->begin_time))
                    <a href="javascript:delProblem({{ $i }})">Delete Problem</a>
                    @endif
                </div>
            @endfor
            
        </div>
        
        <div>
            <input type="submit" value="Submit"/>
        </div>
    </form>
    
    <script language="javascript">
        //hideAllowedUserList();
        @if($contest->contest_type == 0)
            hideAllowedUserList();
        @endif
        var count = {{$problem_count}};
        function addProblem()
        {
            var problemItem = "<div id=p_" + count +" >\n" +
                    "<label>Problem ID</label>\n" +
                    "<input type='text' name=problem_id[]/>\n" +
                    "<label>Problem Title In Contest</label>\n" +
                    "<input type='text' name=problem_name[]/>\n" +
                    "<a href='javascript:delProblem(" + count + ")'>Delete Problem</a>\n"  +
                    "</div>";
            document.getElementById("add_problem").insertAdjacentHTML("beforeEnd",problemItem);
            count++;

        }

        function delProblem(div_id)
        {
            document.getElementById("add_problem").removeChild(document.getElementById("p_" + div_id));
        }

        function showAllowedUserList()
        {
            document.getElementById("allowedUserList").style.display = "block";
        }

        function hideAllowedUserList()
        {
            document.getElementById("allowedUserList").style.display = "none";
        }
    </script>
</body>