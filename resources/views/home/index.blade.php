<!doctype html>
<html>
<head>
    <title>Welcome come to NEU online judge</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#home").addClass("active");
        })
    </script>
</head>
<body class="home_body">
    @include("layout.header")
    <h2 class="text-center">Welcome come to NEU online judge</h2>
        <p class="my_p main text-primary">
            Your time is limited, so don't waste it living someone else's life.
            Don't be trapped by dogma - which is living with the results of other people's thinking.
            Don't let the noise of other's opinions drown out your own inner voice.
            And most important, have the courage to follow your heart and intuition.
            They somehow already know what you truly want to become.
            Everything else is secondary.
            <div class="text-right main text-primary" style="font-size: 20px;">—— Steve Jobs</div>
        </p>
    @include("layout.footer")
</body>
</html>