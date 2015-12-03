<!doctype html>
<html>
<head>
    <title>Welcome come to NEU online judge</title>
    <?php require("./UI/head.php");?>
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/main.js"></script>
</head>
<body>
    <?php require("./UI/header.php");?>
    <main>
        <h1 class="text-center">Welcome come to NEU online judge</h1>

        @if(!isset($noLogin))
            <div>Welcome {{ $username }} !</div>
            <div>You are currently logged in</div>
            @if(!isset($lastlogin_ip))
                <div class="text-info">This is your first time to log in</div>
            @elseif(isset($lastlogin_ip))
                <div class="text-info">Your last login ip is {{ $lastlogin_ip }}</div>
            @endif
        @else
            <div class="text-info">Welcome Guest!</div>
            <div class="text-info">You are currently not logged in</div>
        @endif
        <p></p>

        <div style="height:250px"></div>
    </main>
        <div style="height:30px;"></div>
    <?php require("./UI/footer.php");?>
</body>
</html>