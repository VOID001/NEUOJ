{{ $training->train_name }}
<p>
{{ $training->description }}
<p>
{{ $training->train_chepter }}
<p>
{{ $chepter_in }}
<br>
@for($i = 1; $i <= $training->train_chepter; $i++)
	chepter{{ $i }}
	<br>
	@foreach($chepter[$i] as $problem)
		@if($i <= $chepter_in)
		<a href = '/problem/{{ $problem->problem_id }}'> {{ $problem->title }} </a>
		{{ $problem->problem_id }}
		{{ $problem->title }}
		@if($problem->ac)
		ac
		@endif
		<br>
		@else
		{{ $problem->problem_id }}
		{{ $problem->title }}
		<br>
		@endif
	@endforeach
@endfor
