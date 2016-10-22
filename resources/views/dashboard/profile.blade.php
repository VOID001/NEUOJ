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
            $('input[type=file]').change(function() {
                $(this).siblings('input[type=text]').val($(this).val());
                //image preview
                var fileObj = $(this)[0];
                var windowURL = window.URL || window.webkitURL;
                var $img = $("#dashboard-profile-img");
                var dataURL;
                if (fileObj && fileObj.files && fileObj.files[0]) {
                    dataURL = windowURL.createObjectURL(fileObj.files[0]);
                    $img.attr('src', dataURL);
                } else {
                    dataURL = $file.val();
                    var imgObj = document.getElementById("#dashboard-profile-img");
                    imgObj.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                    imgObj.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = dataURL;
                }
                //get image size
                var size = fileObj.files[0].size || fileObj.files[0].fileSize;
                if(size/1024/1024 >= 1) {
                    $("#dashboard-profile-img").replaceWith("<div id='img_size_hint' class='text-danger' style='padding-left: 36px;font-size: 16px'><br/>文件过大>.<,换一张<br/><br/></div>")
                } else {
                    $("#img_size_hint").replaceWith('<img class="profile-img" alt="努力加载中..." id="dashboard-profile-img" src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif">');
                }
            });
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
				<img class="profile-img" alt="努力加载中..." id="dashboard-profile-img" src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif">
				<table class="profile-table">
				<tbody>
					<tr>
						<td>
                            <div class="image-input">
                                <div class="btn btn-grey">Select</div>
                                <input class="custom-word" type="text" placeholder="未选择文件" />
                                <input name="image" accept="image/*" type="file" />
                            </div>
                        </td>
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
