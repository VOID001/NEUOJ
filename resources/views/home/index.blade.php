<!doctype html>
<html>
<head>
    <title>Welcome come to NEU online judge</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script src="/js/home.js"></script>
</head>
<body class="home_body">
    <div class="home_logo"><img src="/image/neuonlinejudge.png"><div>
    <div class="text-center"><span class="text-success home_text"><kbd>talk is cheap,show me the code </kbd></span></div>
     <div class="dropdownmenu">
         <a class="btn home_button" type="button" id="home_btn">开启旅程&nbsp;&raquo;</a>
        <ul role="menu">
            <li><a role="menuitem" class="btn home_button" href="/problem">Problems</a></li>
            <li><a role="menuitem" class="btn home_button" href="#">NEUACM</a></li>
            <li><a role="menuitem" class="btn home_button" href="#">Donate</a></li>
            <li><a role="menuitem" class="btn home_button" href="#">More</a></li>
            <input id="hidval" type="hidden" value="0"/>
        </ul>
     </div>
</body>
</html>