<!doctype html>
<html>
<head>
	<title>Training</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
	$(function() {
		$('#training').addClass('active');
	})
	</script>
</head>
<body class="">
	@include("layout.header")
	<div class="front-container">
		<div class="training-front-header">
			<img src="/avatar/2" />
			<div>
				<h2>Training System</h2>
				<div>Welcome to Training System, You can choose a training to improve your skill</div>
			</div>
		</div>
		<div class="panel-group training-front-list">
		@for($i = 0; $i < $trainNum; $i++)
			<div class="panel panel-default">
				<div class="panel-heading">
					<a href="/training/{{ $training[$i]->train_id }}">Training-Title : {{ $training[$i]->train_name }}</a>
				</div>
				<div class="panel-body">
					Description :{{ $training[$i]->description }}
				</div>
			</div>
		@endfor
		</div>
	</div>
	@include("layout.footer")
	</body>
</html>