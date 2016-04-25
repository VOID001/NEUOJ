<!doctype html>
<html>
<head>
	<title>Welcome come to NEU online judge</title>
	@include("layout.head")
	<link rel="stylesheet" href="/css/main.css">
	<script type="text/javascript">
		$(function() {
			$("#home").addClass("active");
		})
	</script>
</head>
<body>
	@include("layout.header")
	<h2 class="text-center">Welcome come to NEU online judge</h2>
		<div class="text-primary text-justify" id="homepage">
			<p>Your time is limited, so don't waste it living someone else's life.
				Don't be trapped by dogma - which is living with the results of other people's thinking.
				Don't let the noise of other's opinions drown out your own inner voice.
				And most important, have the courage to follow your heart and intuition.
				They somehow already know what you truly want to become.
				Everything else is secondary.
			</p>
			<p class="text-right text-primary">—— Steve Jobs</p>
		</div>
	@include("layout.footer")
</body>
</html>