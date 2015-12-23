<!doctype html>
<html>
<head>
    <title>Profile</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#dashboard_settings").addClass("dashboard_subnav_active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="col-xs-10 padding_10">

    <div align="center">
    <form action="/dashboard/settings" method="POST">
    {{ csrf_field() }}
        <h3 class="text-center">Settings</h3>
        <div style="height: 20px;padding-left: 38px">
            @if(count($errors) > 0)
                <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{$errors->all()[0]}}</div></div>
            @elseif(isset($settingError))
                <div class="form-group" style="width: 400px;text-align: left"><div class="label label-warning" style="font-size: 13px">{{ $settingError }}</div></div>
            @endif
        </div>
        <table id="profileTable">
                <tr>
                    <td style="padding-left: 20px">Old Password</td>
                    <td><input type="password" name="old_pass" value="" class="form-control"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">New Password</td>
                    <td><input type="password" name="pass" value="" class="form-control"/></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Re-type Password</td>
                    <td><input type="password" name="pass_confirmation" value="" class="form-control"/></td>
                </tr>
                <tr>
                    <td></td>
                    <td class="text-right"><input type="submit" value="Save" class="btn btn-success"></td>
                </tr>
        </table>
    </form>
    </div>
    <div style="height: 192px"></div>

</div>
</body>
</html>