<!doctype html>
<html>
<head>
<title>Training</title>
@include("layout.head")
<link rel="stylesheet" href="/css/main.css">
<script type="text/javascript">
$(function(){
	$('#training').addClass('active');
})
</script>
</head>
<body class="body_grey">
@include("layout.header")
<div class="container-fluid" id="training_container">
	<img src="/avatar/2" class="img_fit">
	<div class="training_title">
		<div>
			<h3>Training System</h3>
			<div>Welcome to Training System, You can choose a training to improve your skill</div>
		</div>
	</div>
</div>
<div class="panel-group training_group">
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
<div style="padding-bottom: 40px"></div>
@include("layout.footer")
</body>
</html>
