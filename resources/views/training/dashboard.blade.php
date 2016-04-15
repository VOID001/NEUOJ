<a href = '/dashboard/training/add'>Add</a>
<br>
<br>

@for($i = 0; $i < $trainNum; $i++)
<a href = '/training/{{ $training[$i]->train_id }}'>{{ $training[$i]->train_name }}</a>
<a class="btn btn-default" href="/dashboard/training/{{ $training[$i]->train_id }}">Edit Contest</a>


<form method="post" action="/dashboard/training/{{ $training[$i]->train_id }}"class="dashboard_problem_table_form" onsubmit = "return validator()">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
<input type="submit"class="btn btn-default" value="delete training"/>
</form>
<br>

@endfor
<script language="Javascript">
    function validator()
    {
        if(confirm("confirm")==true)
            return true;
        else
            return false;
    }
</script>