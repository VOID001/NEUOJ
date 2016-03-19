Problem is used by the following contests:
@foreach($usedContestList as $contest)
	{{ $contest }}
@endforeach
<meta http-equiv="refresh" content="2; url={{ $_SERVER['HTTP_REFERER'] }}">