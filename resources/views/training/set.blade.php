<!doctype html>
<html>
<head>
    <title>Set Training</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/searchFunction.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#dashboard_training").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
    @include("layout.dashboard_nav")
    <div class="col-xs-10">
        <h3 class="text-center">Set Training</h3>
        @foreach($errors->all() as $error)
        <div>
            <ul>{{ $error }}</ul>
        </div>
        @endforeach
        <form id="contest_add_form" class="form-inline" action="/dashboard/training/{{ $train_info->train_id }}" method='post'>
            {{ csrf_field() }}
            <label>Train Name</label>
            <input type = "text" name = "train_name" value = "{{ $train_info->train_name }}"/>
            <label>Description</label>
            <input type = "text" name = "description" value = "{{ $train_info->description }}"/>
            <div id = "add_contest">
                <a href="javascript:addChapter()">Add Chapter</a>
                <a href="javascript:deleteChapter()">Delete The Last Chapter</a>
                <br>
                @for($i = 1; $i <= $train_info->train_chapter; $i++)
                <div id = 'chapter_{{ $i }}'>
                    <br>Chapter {{ $i }}
                    <a href = "javascript:addProblem({{ $i }})"> AddProblem</a><br>
                    @foreach($train_problem as $problem)
                        @if($problem->chapter_id == $i)
                            <div id = "p_{{ $problem->train_problem_id+1 }}">
                            <input type = "hidden" name = "problem_chapter[]" value = {{ $i }} />
                            <label>Problem ID</label>
                            <div class="search-container">
                                <input class="search-title problem-id" type = "text" name = "problem_id[]" value = "{{ $problem->problem_id }}" autocomplete="off" />
                                <div class="search-option hidden"></div>
                            </div>
                            <label>Problem Name</label>
                            <input class="problem-title" type = "text" name = "problem_name[]" value = "{{ $problem->problem_title }}" autocomplete="off" />
                            <a href = "javascript:deleteProblem({{ $i }}, {{ $problem->problem_id }})">Delete Problem</a>
                            </div>
                        @endif
                    @endforeach
                </div>
                @endfor
            </div>
            <input type="hidden" name="train_chapter" id="train_chapter" value= {{ $train_info->train_chapter }} />
            <input type="submit" value="Submit"/>
        </form>

    </div>
</body>
</html>






<script language="javascript">
    var titleData = [];
    $.ajax({
        url: '/ajax/problem_title',
        type: 'GET',
        async: true,
        dataType: 'json',
        success: function(result) {
            bindSearchFunction(result);
            titleData = result;
        }
    });
    var chapterCount = {{ $train_info->train_chapter }} + 1;
    var problemCount = {{ $train_problem_count }};
    function addChapter()
    {
        var chapterItem = "<div id = 'chapter_" + chapterCount + "'><br>Chapter" + chapterCount + "<a href = \"javascript:addProblem(" + chapterCount + ")\">Add Problem</a><br></div>";
        document.getElementById("add_contest").insertAdjacentHTML("beforeEnd", chapterItem);
        document.getElementById("train_chapter").value = chapterCount;
        chapterCount++;
    }

    function deleteChapter()
    {
        document.getElementById("add_contest").removeChild(document.getElementById("chapter_" + (chapterCount-1)));
        chapterCount--;
        document.getElementById("train_chapter").value = chapterCount-1;
    }

    function addProblem(chapter_id)
    {
        var problemItem =  "<div id = p_" + problemCount + "><label>Problem ID</label>"+
                           "<input type = \"hidden\" name = \"problem_chapter[]\" value = " + chapter_id + "/>"+
                           "<div class='search-container'>"+
                           "<input class=\"search-title problem-id\" type = \"text\" name = \"problem_id[]\" autocomplete=\"off\" />"+
                           "<div class=\"search-option hidden\"></div>"+
                           "</div>"+
                           "<label>Problem Name</label>"+
                           "<input class=\"problem-title\" type = \"text\" name = \"problem_name[]\" autocomplete=\"off\" />"+
                           "<a href=\"javascript:deleteProblem("+chapter_id+","+problemCount+")\">Delete Problem</a><br></div>";
        document.getElementById("chapter_"+chapter_id).insertAdjacentHTML("beforeEnd", problemItem);
        problemCount++;
        bindSearchFunction(titleData);
    }

    function deleteProblem(chapter_id, div_id)
    {
        document.getElementById("chapter_"+chapter_id).removeChild(document.getElementById("p_" + div_id));
    }
</script>