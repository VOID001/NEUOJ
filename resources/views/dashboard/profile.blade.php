<!doctype html>
 <html>
 <div class="container-fluid">
	 <title>Profile</title>
	 @include("layout.head")
	 @include("layout.dashboard_header")
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
	 <div class="row">
			 @include("layout.dashboard_nav")
				 <div class="col-md-5" id="dashboard-profile-info-content-user">
								 <form action="/dashboard/profile" method="POST" enctype="multipart/form-data">
											 {{ csrf_field() }}
											 <div class="text-center">
												 @if(count($errors) > 0)
													 <div class="label label-warning" id="dashboard-label-warning">{{$errors->all()[0]}}</div>
												 @elseif(isset($profileError))
													 <div class="label label-warning" id="dashboard-label-warning">{{ $profileError }}</div>
												 @endif
											 </div>
											 <div class='profile-info'>
												 <div class='profile-content'>
													 <div class="profile-text">
														 <section>
															 <table class="custom-table" id="dashboard-custom-table">
																 <tbody>
																 <tr>
																	 <td>Realname(NEU必填)</td>
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
																	 <td>Student ID</td>
																	 <td><input class="form-control" name="stu_id" value="@if(isset($stu_id)){{ $stu_id }}@endif" type="text"></td>
																 </tr>
																 <div class="image-input">
																	 <div class="btn btn-grey">Select</div>
																	 <input class="custom-word" type="text" placeholder="No file selected" />
																	 <input name="image" accept="image/*" type="file" />
																 </div>
																 <p class="profile-note">Note: Click the "select" button to edit your avatar and make sure your image file is less than 1M!</p>
																 </tbody>
															 </table>
															 <input class="btn profile-button" value="Save" type="submit">
														 </section>
													 </div>
												 </div>
											 </div>
								 </form>
				 </div>
				 <div class="col-md-3">
						<div class="dashboard-profile-info-content-problem">
							<div class='dashboard-problem'>
								<div class='dashboard-problem-content'>
									<p class="text-center">Unsolved Problems</p>
									<hr>
									<div id="dashboard-unsolved-problems"></div>
									<!--<hr>
									<p class="text-center">Solved Problems</p>
									<hr>
									<div id="dashboard-solved-problems"></div>-->
								</div>
							</div>
						</div>

				 </div>
	 </div>
	 <div class="row">
		 <div class="col-md-12" id="dashboard-profile-chart">
			<div class="profile-chart">
				<canvas id="acCountChart" width="600" height="400"></canvas>
				<script type="text/javascript">
					var acCountChartData = '{!! $acCount or NULL!!}';
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
									backgroundColor: "rgba(220,220,220,0.2)",
									borderWidth:1.5,
									borderColor: "#DCDCDC",
									borderCapStyle: 'butt',
									borderDash: [],
									borderDashOffset: 0.0,
									borderJoinStyle: 'miter',
									pointBorderColor: "rgba(220,220,220,1)",
									pointBackgroundColor: "#fff",
									pointRadius: 4,
									pointHitRadius: 10,
									pointBorderWidth: 1,
									pointHoverRadius: 8,
									pointHoverBackgroundColor: "#fff",
									pointHoverBorderColor: "#0093ff",
									pointHoverBorderWidth: 1,
									pointStyle:'circle',
									scaleGridLineColor : "#000"
								}
							]
						},
						options:{
							scales:{
								yAxes:[{
									ticks: {
										max: maxMyAcCount,
										stepSize: 4
									}
								}]
							}
						}
					});
				</script>
			</div>
		 </div>
	 </div>
	 <script>
		$(function(){
			$.ajax({
				url: '/ajax/unfinished_problems',
				type: 'GET',
				async: true,
				dataType: 'json',
				success: function(data) {
					console.log(data);
					var obj = eval(data).unfinished_problems;
					var times = obj.length > 15 ? 15 : obj.length;
					for(var i=0;i<times;i++) {
						var problem =
								'<a href="/problem/' + obj[i].pid + '">&nbsp;' + obj[i].title+ '</a>';
						$("#dashboard-unsolved-problems").append(problem);
					}
				}
			})
		})
	 </script>
</html>
