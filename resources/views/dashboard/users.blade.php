<!doctype html>
<html>
<head>
    <title>Setting</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function() {
            $("#dashboard_users").addClass("dashboard-subnav-active");
        })
    </script>
</head>
<body>
@include("layout.dashboard_nav")
<div class="back-container">
    <h1>Now we only support enable teacher and disable teacher</h1>
    <form method="POST" action="/dashboard/set_teacher">
        {{ csrf_field() }}
        <input type="text" name="username"/>
        <input type="Submit" value="Toggle Teacher"/>
    </form>

    <h3>Admin</h3>
    <table>
        <thead>
        <th>ID</th>
        <th>Username</th>
        <th>Last login IP</th>
        </thead>
        <tbody>
        @foreach($admin_list as $admin)
            <tr>
                <td>{{ $admin->uid }}</td>
                <td>{{ $admin->username }}</td>
                <td>{{ $admin->lastlogin_ip }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h3>Teacher</h3>
    <table>
        <thead>
        <th>ID</th>
        <th>Username</th>
        <th>Last login IP</th>
        </thead>
        <tbody>
        @foreach($teacher_list as $teacher)
            <tr>
                <td>{{ $teacher->uid }}</td>
                <td>{{ $teacher->username }}</td>
                <td>{{ $teacher->lastlogin_ip }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
