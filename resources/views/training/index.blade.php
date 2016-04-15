{{ $training->train_name }}
<p>
{{ $training->description }}
<p>
{{ $training->train_chapter }}
<p>
{{ $chapter_in }}
<br>
@for($i = 1; $i <= $training->train_chapter; $i++)
	chapter{{ $i }}
	<br>
	@foreach($chapter[$i] as $problem)
		@if($i <= $chapter_in)
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
