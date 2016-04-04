<form action="/dashboard/training/add" method='post'>
{{ csrf_field() }}
<label>Train Name</label>
<input type = "text" name = "train_name"/>
<label>Chepter Num</label>
<input type = "text" name = "train_chepter" value = 2 />
<br>
<p>chepter 1</p>
<label>Problem ID</label>
<input type = "hidden" name = "problem_chepter[]" value = 1/>
<input type = "text" name = "problem_id[]" />
<label>Problem Name</label>
<input type = "text" name = "problem_name[]" />
<br>

<label>Problem ID</label>
<input type = "hidden" name = "problem_chepter[]" value = 1/>
<input type = "text" name = "problem_id[]" />
<label>Problem Name</label>
<input type = "text" name = "problem_name[]" />
<br>


<p>chepter 2</p>
<label>Problem ID</label>
<input type = "hidden" name = "problem_chepter[]" value = 2/>
<input type = "text" name = "problem_id[]" />
<label>Problem Name</label>
<input type = "text" name = "problem_name[]" />
<br>

<label>Problem ID</label>
<input type = "hidden" name = "problem_chepter[]" value = 2/>
<input type = "text" name = "problem_id[]" />
<label>Problem Name</label>
<input type = "text" name = "problem_name[]" />
<br>

<label>Problem ID</label>
<input type = "hidden" name = "problem_chepter[]" value = 2/>
<input type = "text" name = "problem_id[]" />
<label>Problem Name</label>
<input type = "text" name = "problem_name[]" />
<br>
<input type="submit" value="Submit"/>
</form>

