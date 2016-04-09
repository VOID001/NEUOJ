<form action="/dashboard/training/add" method='post'>
{{ csrf_field() }}
<label>Train Name</label>
<input type = "text" name = "train_name"/>
<div id = "add_contest">
<a href="javascript:addChapter()">Add Chapter</a>
<a href="javascript:deleteChapter()">Delete The Last Chapter</a>
<br>
</div>
<input type="submit" value="Submit"/>
</form>






<script language="javascript">
	var chapterCount = 1;
	var problemCount = 0;
	function addChapter()
	{
		var chapterItem = "<div id = 'chapter_" + chapterCount + "'><br>Chapter" + chapterCount + "<a href = \"javascript:addProblem(" + chapterCount + ")\">Add Problem</a><br></div>";
		document.getElementById("add_contest").insertAdjacentHTML("beforeEnd", chapterItem);
		chapterCount++;
	}

    function deleteChapter()
    {
        document.getElementById("add_contest").removeChild(document.getElementById("chapter_" + (chapterCount-1)));
        chapterCount--;
    }

	function addProblem(chapter_id)
	{
		var problemItem =  "<div id = p_" + problemCount + "><label>Problem ID</label>"+
						   "<input type = \"hidden\" name = \"problem_chepter[]\" value = " + chapter_id + "/>"+
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