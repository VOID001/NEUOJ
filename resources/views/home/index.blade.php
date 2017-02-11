<!doctype html>
<html>
<head>
<title>Welcome come to NEU online judge</title>
@include("layout.head")
<link rel="stylesheet" href="/css/main.css">
<link rel="stylesheet" href="/css/onepage-scroll.css" />
<script src="/js/jquery.onepage-scroll.min.js"></script>
<script>
  $(function(){
  	$('.main').onepage_scroll({
  		sectionContainer: '.page'
  	});
  	$("#home").addClass("active");
  });
</script>
</head>
<body>
<style type="text/css">
.navbar{
    position: fixed;
    border-radius: 0;
    border: 0;
    height: 50px;
    background-color: #3f51b5;
    display: block;
    width: 100%;
    z-index: 10;
}
</style>
  @include("layout.header")
  @if(Request::session()->get('username') == '')
	<div class="main">
		<div class="page page1">
			<p class="page-title">Welcome to NEUOJ</p>
			<div class='page-info'>
    				<p>欢迎使用 NEUOJ ver {{ env('APP_VER', 'NULL') }}, <a href="http://202.118.31.226:3322/Lbyang/NEUOJ"><img src="http://202.118.31.226:3322/api/badges/Lbyang/NEUOJ/status.svg" /></a>在使用中遇到任何问题, 请在 <a href="https://github.com/VOID001/NEUOJ-bug-report/issues/new">[Github Issues]</a>提出, 或者联系: zhangjianqiu13@gmail.com<p>
    			</div>
    			<p class='page-tip'>↓滚动</p>
		</div>
		<div class="page page2">
                    <div class='title-icon'><span class="glyphicon glyphicon-send"></span></div>
			<p class="title"><a href="/problem/p/1">Problem</a></p>
                    <div class='title-info'><span>多多益善的题量，分布式测评，满足不断前行的你</span></div>
		</div>
		<div class="page page3">
                    <div class='title-icon'><span class="glyphicon glyphicon-globe"></span></div>
			<p class="title"><a href="/contest/p/1">Contest</a></p>
                    <div class='title-info'><span>支持public  private  register多种比赛模式</span></div>
		</div>
		<div class="page page4">
                    <div class='title-icon'><span class="glyphicon glyphicon-cloud-download"></span></div>
			<p class="title"><a href="/training">Training</a></p>
                    <div class='title-info'><span>不同难度等级进阶训练，每天都有小收获</span></div>
		</div>
	</div>
  @else
	<p class="homesignin-text text-center">Welcome , Have a nice coding journey today !</p>
    	<div class='homesignin-message-group'>
    		 <div class='homesignin-contest'>
			<div class='homesignin-contest-line'></div>
			<div class='homesignin-contest-content'>
				<h4 class="text-center">Running Contests</h4>
				<hr>
          				<ul id="contest_list" class="list-unstyled"></ul>
				<div class="tip">
					<a class="custom-word" href="/contest/p/1">See All ...</a>
				</div>
			</div>
		</div>
		<div class='homesignin-training'>
			<div class='homesignin-training-line'></div>
			<div class='homesignin-training-content'>
				<h4 class="text-center">Trainings</h4>
				<hr>
				<ul id="training_list" class="list-unstyled"></ul>
				<div class="tip">
                				<a class="custom-word" href="/training">See All ...</a>
                			</div>
			</div>
		</div>
		<div class='homesignin-problem'>
			<div class='homesignin-problem-line'></div>
			<div class='homesignin-problem-content'>
				<h4 class="text-center">Unsolved Problems</h4>
				<hr>
				<ul id="problem_list" class="list-unstyled"></ul>
				<div class="tip">
					<a class="custom-word" href="/problem/p/1">See All ...</a>
				</div>
			</div>
		</div>
		<div style="clear: both"></div>
	</div>
  
    <script>
      $(function(){
        $.ajax({
          url: '/ajax/contests',
          type: 'GET',
          async: true,
          dataType: 'json',
          success: function(data) {
            var obj = eval(data).contests;
            var times = obj.length > 7 ? 7 : obj.length;
            for(var i=0;i<times;i++) {
              var contest = '<li>' +
                  '<a class="custom-word" href="/contest/' + obj[i].contest_id + '">&nbsp;' + (i+1) + '. ' +  obj[i].contest_name+ '</a></li>';
              $("#contest_list").append(contest);
            }
          }
        })
        $.ajax({
          url: '/ajax/trainings',
          type: 'GET',
          async: true,
          dataType: 'json',
          success: function(data) {
            console.log(data);
            var obj = eval(data);
            var times = obj.length > 7 ? 7 : obj.length;
            for(var i=0;i<times;i++) {
              var training = '<li>' +
                  '<a class="custom-word" href="/training/' + obj[i].train_id + '">&nbsp;'+ (i+1) + '. ' + obj[i].train_name+ '</a></li>';
              $("#training_list").append(training);
            }
          }
        })

        $.ajax({
          url: '/ajax/unfinished_problems',
          type: 'GET',
          async: true,
          dataType: 'json',
          success: function(data) {
            console.log(data);
            var obj = eval(data).unfinished_problems;
            var times = obj.length > 7 ? 7 : obj.length;
            for(var i=0;i<times;i++) {
              var problem = '<li>' +
                  '<a class="custom-word" href="/problem/' + obj[i].pid + '">&nbsp;' + (i+1) + '. ' +  obj[i].title+ '</a></li>';
              $("#problem_list").append(problem);
            }
          }
        })

          $.ajax({
              url: '/ajax/username',
              type: 'GET',
              async: true,
              dataType: 'json',
              success: function(data) {
                  console.log(data);
                  var socket = io.connect("http://localhost:3000");
                  var username =data['username'];
                  socket.emit('login', username);
              }
          })

      })
    </script>
    @include("layout.footer")
  @endif
</body>
</html>