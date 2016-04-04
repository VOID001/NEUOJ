@for($i = 0; $i < $trainNum; $i++)
<a href = '/training/{{ $training[$i]->train_id }}'>{{ $training[$i]->train_name }}</a>
@endfor