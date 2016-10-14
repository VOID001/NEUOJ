<!doctype html>
 <html>
 <head>
 	<title>Profile</title>
 	@include("layout.head")
 	<link rel="stylesheet" href="/css/main.css">
            <script type="text/javascript" src="/js/Chart.min.js"></script>
 	<script type="text/javascript">
 		$(function() {
 			$("#dashboard_profile").addClass("dashboard-subnav-active");
 		})
 	</script>
 </head>
 <body>
 	@include("layout.dashboard_nav")
 	<div class="back-container">
 		<h3 class="custom-heading">Profile</h3>
 		<form action="/dashboard/profile" method="POST" enctype="multipart/form-data">
 			{{ csrf_field() }}
 			<div class="text-center">
 				@if(count($errors) > 0)
 					<div class="label label-warning">{{$errors->all()[0]}}</div>
 				@elseif(isset($profileError))
 					<div class="label label-warning">{{ $profileError }}</div>
 				@endif
 			</div>
                                    <div class='profile-info'>
                                    <div class="profile-title"></div>
                                    <div class='profile-content'>
 			<div class="profile-avater">
				<img class="profile-img" id="dashboard-profile-img" src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif">
				<table class="profile-table">
				<tbody>
					<tr>
						<td>Avatar</td>
						<td><input name="image" accept="image/*" type="file"></td>
					</tr>
				</tbody>
				</table>
				<p class="profile-note">Note:Please make sure your image file is less than 1M!</p>
			</div>
			<div class="profile-text">
			<section>			
				<table class="custom-table">
				<tbody>
					<tr>
						<td>真实姓名(NEU必填)</td>
						<td><input class="form-control" name="realname" value="@if(isset($realname)){{ $realname }}@endif" placeholder="This will be shown only to admin" type="text"></td>
					</tr>
					<tr>
						<td>Nickname</td>
						<td><input class="form-control" name="nickname" value="@if(isset($nickname)){{ $nickname }}@endif" type="text"></td>
					</tr>
					<tr>
						<td>School</td>
						<td><input class="form-control" name="school" value="@if(isset($school)){{ $school }}@endif" type="text"></td>
					</tr>
					<tr>
						<td>学号</td>
						<td><input class="form-control" name="stu_id" value="@if(isset($stu_id)){{ $stu_id }}@endif" type="text"></td>
					</tr>
				</tbody>
				</table>
				<input class="btn profile-button" value="Save" type="submit">
			</section>
                                        </div>
                                        </div>
                                        <div class="profile-chart">
                                        <canvas id="acCountChart" width="600" height="400"></canvas>
                                        <script type="text/javascript">
                                            var acCountChartData = '{!! $acCount !!}';
                                            var jsonData = JSON.parse(acCountChartData);
                                            console.log(jsonData);
                                            var labels = new Array();
                                            var myData = new Array();
                                            var maxMyAcCount = 0
                                            for(var i = jsonData.length - 1; i >= 0; i--)
                                            {
                                                if(jsonData[i].count != 0)
                                                {
                                                    if(jsonData[i].count > maxMyAcCount)
                                                        maxMyAcCount = jsonData[i].count;
                                                    labels.push(jsonData[i].date);
                                                    myData.push(jsonData[i].count);
                                                }
                                            }
                                            var ctx = $('#acCountChart');
                                            /* Frontend should fix the chart style and display */

                                            var myChart = new Chart(ctx, {
                                                type: 'line',
                                                data: {
                                                    labels: labels,
                                                    datasets:[
                                                        {
                                                            data: myData,
                                                            //backgroundColor: fillPattern, Leave it as default, frontend fix it
                                                            label: "AC Counts Per Day",
                                                            fill: true,
                                                            lineTension: 0.3,
                                                            backgroundColor: "#D1EAED",
                                                            borderWidth:1.5,
                                                            borderColor: "#2E87D8",
                                                            borderCapStyle: 'butt',
                                                            borderDash: [],
                                                            borderDashOffset: 0.0,
                                                            borderJoinStyle: 'miter',
                                                            pointBorderColor: "#0093ff",
                                                            pointBackgroundColor: "#fff",
                                                            pointRadius: 4,
                                                            pointHitRadius: 10,
                                                            pointBorderWidth: 1,
                                                            pointHoverRadius: 8,
                                                            pointHoverBackgroundColor: "#fff",
                                                            pointHoverBorderColor: "#0093ff",
                                                            pointHoverBorderWidth: 1,
                                                            pointStyle:'circle',
                                                            scaleGridLineColor : "#000",
                                                        }
                                                    ]
                                                },
                                                options:{
                                                    scales:{
                                                        yAxes:[{
                                                            ticks: {
                                                                max: maxMyAcCount,
                                                                stepSize: 4,
                                                            }
                                                        }]
                                                    }
                                                }
                                            });
                                        </script>
                                        </div>
                                </div>
                        </form>
            </div>
 </body>
</html>