<!doctype html>
<html>
<head>
  <title>Users</title>
  @include("layout.head")
  @include("layout.dashboard_header")
  <link rel="stylesheet" href="/css/main.css">
</head>
<body>
@include("layout.dashboard_nav")
<div class="back-container">
  <h2 class="custom-heading" id="dashboard-custom-heading">Users</h2>
  <div class="back-list">
    <select id="role-select" class="form-control">
      <option value="/dashboard/users">User</option>
      <option value="/dashboard/users?role=teacher">Teacher</option>
      <option value="/dashboard/users?role=admin">Admin</option>
    </select>
    <div class="pull-right">
      <form action="/dashboard/users">
        @if($role != NULL)
          <input type="hidden" name="role" value="{{ $role }}">
        @endif
        <input name="username" value="@if(isset($search_username)){{ $search_username }}@endif">
        <input type="submit" class="btn btn-primary" value="Search">
      </form>
    </div>
    <table class="table table-bordered table-hover custom-list">
      <thead>
      <th width="7%">uid</th>
      <th width="10%">username</th>
      <th width="10%">character</th>
      <th width="13%">registration_time</th>
      <th width="13%">lastlogin_time</th>
      <th width="22%">email</th>
      <th>grant teacher</th>
      <th>grant admin</th>
      </thead>
      <tbody>
      @foreach($users as $user)
        <tr>
          <td>{{ $user->uid }}</td>
          <td>{{ $user->username }}</td>
          <td>
            @if($user->gid == 1)Admin
            @elseif($user->gid == 2)Teacher
            @elseif($user->gid == 0)User
            @endif
          </td>
          <td>{{ $user->registration_time }}</td>
          <td>{{ $user->lastlogin_time }}</td>
          <td>{{ $user->email }}</td>
          <td>
            @if($user->gid == 0)
              <a href="/dashboard/users/toggle?gid=2&uid={{ $user->uid }}" class="btn btn-primary">Grant Teacher</a>
            @elseif($user->gid == 1)
              <a class="btn btn-default">Permitted</a>
            @else
              <a href="/dashboard/users/toggle?gid=0&uid={{ $user->uid }}" class="btn btn-danger">Grant User</a>
            @endif
          </td>
          <td>
            @if($user->gid == 0 || $user->gid == 2)
              <a href="/dashboard/users/toggle?gid=1&uid={{ $user->uid }}" class="btn btn-success">Grant Admin</a>
            @else
              <a class="btn btn-default">Permitted</a>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
    <div class="text-center" id="callBackPager"></div>
  </div>
</div>
<script src="/js/extendPagination.js"></script>
<script src="/js/getUrlParam.js"></script>
<script>
  $(function () {
    /*add active status for btn*/
    $("#dashboard_users").addClass("dashboard-subnav-active");
    /*pagination*/
    var targetHerf = "/dashboard/users?"
    @if($role != NULL)
    targetHerf += "role={{ $role }}&";
    @endif
    @if($search_username != "")
    targetHerf += "username={{ $search_username }}&"
    @endif
    targetHerf += "page_id=";
    $("#callBackPager").extendPagination({
      totalPage: {{ $page_num }},
      showPage: 5,
      pageNumber: {{ $page_id }}
      }, targetHerf);
    /*select*/
    var role = $.getUrlParam('role');
    if(role == null) {
      role = "User";
    } else {
      role = role[0].toUpperCase() + role.substr(1);
    }
    var $option = $("#role-select option");
    $option.each(function() {
      if($(this).text() == role)  {
        $(this).attr("selected", true);
      }
    });
    $("#role-select").change(function() {
      window.location.href = $(this).val();
   })
  })
</script>
</body>
</html>
