<!doctype html>
<html>
<head>
    <title>Profile</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_profile").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="col-xs-10 padding_10">

        <h3 class="text-center">Profile</h3>
        <div align="center">
            <form action="/dashboard/profile" method="POST" enctype="multipart/form-data" id="profileForm">
            {{ csrf_field() }}
                <div style="height: 20px;padding-left: 38px">
                    @if(count($errors) > 0)
                        <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{$errors->all()[0]}}</div></div>
                    @elseif(isset($profileError))
                        <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{ $profileError }}</div></div>
                    @endif
                </div>
                <img src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif" style="width:120px;height:120px;"/>
                <table id="profileTable">
                        <tr>
                            <td style="padding-left: 20px">Avatar</td>
                            <td><input name="image" type="file" accept="image/*"></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px">Nickname</td>
                            <td><input type="text" name="nickname" value="@if(isset($nickname)){{ $nickname }}@endif" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px">School</td>
                            <td><input type="text" name="school" value="@if(isset($school)){{ $school }}@endif" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td style="padding-left: 20px">学号</td>
                            <td><input type="text" name="stu_id" value="@if(isset($stu_id)){{ $stu_id }}@endif" class="form-control"/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td class="text-right"><input type="submit" value="Save" class="btn btn-success"></td>
                        </tr>
                </table>
                <p>Note: Please make sure your image file is less than 1M!</p>
            </form>
        </div>
    </div>

</body>
</html>
