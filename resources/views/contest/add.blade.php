<h1>Add Contest</h1>
@foreach($errors->all() as $error)
    <div>
        <ul>{{ $error }}</ul>
    </div>
@endforeach
<form action="/dashboard/contest/add" method="post">
    {{ csrf_field() }}
    <div>
        <label>Contest Name</label>
        <input type="text" name="contest_name" value="{{ old('contest_name') }}"required/>
    </div>
    <div>
        <label>Begin Time</label>
        <input type="datetime-local" name="begin_time" value="{{ old('begin_time') }}"required/>
    </div>
    <div>
        <label>End Time</label>
        <input type="datetime-local" name="end_time" value="{{ old('end_time') }}"required/>
    </div>
    <div>
        <label>Contest Type</label>
        <input type="radio" name="contest_type" value="public" onclick="hideAllowedUserList()" checked/>public
        <input type="radio" name="contest_type" value="private" onclick="showAllowedUserList()"/>private
    </div>
    <div id="allowedUserList">
        <div>
            <label>Import user list from file</label>
            <input type="file" name="user_list"/>
        </div>
        <div>
            <label>Input Allowed User name</label>
            <div>
                <textarea name="user_list" placeholder="Input the user name , seperate each with comma"></textarea>
            </div>
            <div id="add_user_list">
                <!-- ID map to username -->
            </div>
        </div>
    </div>
    <!-- This part is used to add problem , now we use Blade php to add problems -->
    <div id="add_problem">
        <label>Select Problem</label>
        <a href="javascript:addProblem()">Add Problem</a>
        @if(session('problem_count'))
            @for($i = 0, $problem = session('current_problem'); $i < session('problem_count'); $i++)
                <div id="p_{{ $i }}">
                    <label>Problem ID</label>
                    <input type="text" name="problem_id[]" value="{{ $problem[$i]['problem_id'] }}"/>
                    <label>Problem Title In Contest</label>
                    <input type="text" name="problem_name[]" value="{{ $problem[$i]['problem_name'] }}"/>
                    <a href="javascript:delProblem({{ $i }})">Delete Problem</a>
                </div>
            @endfor
        @endif
        <!-- How to make it work -->
    </div>
    <!-- This part is used to add problem , now we use Blade php to add problems -->
    <div>
        <input type="submit" value="Submit"/>
    </div>
</form>

<script language="javascript">
    hideAllowedUserList();
    var count = 0;
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
