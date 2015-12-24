<!doctype html>
<html>
<head>
    <title>Profile</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
</head>
<body>
    @include("layout.header")

    <div align="center">
        <h3 class="text-center">Profile</h3>
        <table id="profileTable">
                <tr>
                    <td style="padding-left: 20px"><img src="/avatar/@if(isset($uid)){{ $uid }}@else{{ 0 }}@endif" style="width:120px;height:120px;"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Nickname</td>
                    <td><input type="text" name="nickname" value="@if(isset($nickname)){{ $nickname }}@endif" readonly="true" class="form-control"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">School</td>
                    <td><input type="text" name="school" value="@if(isset($school)){{ $school }}@endif" readonly="true" class="form-control"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">学号</td>
                    <td><input type="text" name="stu_id" value="@if(isset($stu_id)){{ $stu_id }}@endif" readonly="true" class="form-control"/></td>
                </tr>
        </table>
    </div>
    <div style="height: 192px"></div>

    @include("layout.footer")
</body>
</html>