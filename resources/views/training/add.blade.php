<!doctype html>
<html>
<head>
    <title>Add Training</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_training").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="col-xs-10">
<h3 class="text-center">Add Training</h3>
@foreach($errors->all() as $error)
        <div>
            <ul>{{ $error }}</ul>
        </div>
@endforeach
<form id="contest_add_form" class="form-inline" action="/dashboard/training/add" method='post'>
{{ csrf_field() }}
<label>Train Name</label>
<input type = "text" name = "train_name"/>
<label>Description</label>
<input type = "text" name = "description"/>
<div id = "add_contest">
<a href="javascript:addChapter()">Add Chapter</a>
<a href="javascript:deleteChapter()">Delete The Last Chapter</a>
<br>
</div>
<input type="hidden" name="train_chapter" id="train_chapter" value=0 />
<input type="submit" value="Submit"/>
</form>

</div>
</body>
</html>






<script language="javascript">
    var chapterCount = 1;
    var problemCount = 0;
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
                           "<input type = \"text\" name = \"problem_id[]\" />"+
                           "<label>Problem Name</label>"+
                           "<input type = \"text\" name = \"problem_name[]\"/>"+
                           "<a href=\"javascript:deleteProblem("+chapter_id+","+problemCount+")\">Delete Problem</a><br></div>";
        document.getElementById("chapter_"+chapter_id).insertAdjacentHTML("beforeEnd", problemItem);
        problemCount++;
    }

    function deleteProblem(chapter_id, div_id)
    {
        document.getElementById("chapter_"+chapter_id).removeChild(document.getElementById("p_" + div_id));
    }
</script>