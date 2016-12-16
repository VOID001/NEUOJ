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
  <div class="modal-list">
    <div class="modal fade user-modal" id="generate_user_modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Generate Users</h4>
          </div>
          <div class="modal-body">
            <form enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row">
                <label class="col-md-4">Contest ID</label>
                <input id="generate_contest_id" class="col-md-8 form-control" type="text" required>
              </div>
              <div class="row">
                <label class="col-md-4">School</label>
                <input id="generate_school" class="col-md-8 form-control" type="text" required>
              </div>
              <div class="row">
                <label class="col-md-4">Number</label>
                <input id="generate_number" class="col-md-8 form-control" type="text" required>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <a class="btn btn-default" type="button"  data-dismiss="modal">Cancel</a>
            <a class="btn btn-primary" type="button" id="generate_user_btn_modal" data-loading-text="Generating, waiting...">Generate</a>
          </div>
        </div>
      </div>
    </div>
    <div id="random_user_table_box">
      <table id="random_user_table">
        <tr><td>username</td><td>password</td></tr>
      </table>
    </div>
    <div class="modal fade user-modal" id="delete_user_modal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Delete Users</h4>
          </div>
          <div class="modal-body">
            <form enctype="multipart/form-data">
              {{ csrf_field() }}
              <div class="row">
                <label class="col-md-4">Contest ID</label>
                <input id="delete_contest_id" class="col-md-8 form-control" type="text" required>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <a class="btn btn-default" type="button"  data-dismiss="modal">Cancel</a>
            <a class="btn btn-danger" type="button" id="delete_user_btn_modal" data-loading-text="Deleting, waiting...">Delete</a>
          </div>
        </div>
      </div>
    </div>
    <div class="modal fade user-modal" id="hint" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
            <h4 class="modal-title text-center">Hint</h4>
          </div>
          <div class="modal-body">
            <div class="hint-content"></div>
          </div>
          <div class="modal-footer">
            <a class="btn btn-default" type="button"  data-dismiss="modal" onclick="window.location.reload();">Colse</a>
          </div>
        </div>
      </div>
    </div>
  </div>
  <h2 class="custom-heading" id="dashboard-custom-heading">Users</h2>
  <div class="back-list">
    <div class="row tool-row">
      <div class="pull-right">
        <a id="generate-user-btn" class="btn btn-grey operate-user-btn" href="#generate_user_modal" data-toggle="modal">Generate</a>
        <a id="delete-user-btn" class="btn btn-danger operate-user-btn" href="#delete_user_modal" data-toggle="modal">Delete</a>
      </div>
    </div>
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
        <input type="submit" class="btn btn-grey operate-user-btn" value="Search">
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
<script src="/js/tableExport.js"></script>
<script src="/js/jquery.base64.js"></script>
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
    });
    /*generate-user*/
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    $('#generate_user_btn_modal').click(function() {
      var $contestId = $('#generate_contest_id');
      var $school = $('#generate_school');
      var $number = $('#generate_number');
      var contestId = $contestId.val().trim();
      var school = $school.val().trim();
      var number = $number.val().trim();
      var flag = true;
      if(contestId == '') {
        $contestId.attr('placeholder', 'Required...');
        flag = false;
      }
      if(school == '') {
        $school.attr('placeholder', 'Required...');
        flag = false;
      }
      if(number == '') {
        $number.attr('placeholder', 'Required...');
        flag = false;
      }
      if(flag == false) {
        return false;
      } else {
        $(this).button('loading');
        $.ajax({
          url: '/dashboard/contest/randusers/' + contestId + '/' + school + '/' + number,
          type: 'POST',
          async: 'true',
          dataType: 'json',
          success: function(json) {
            if(json['error'] != '' && json['error'] != null) {
              $('#generate_user_modal').modal('hide');
              $('.hint-content').text(json['error']);
              $('#hint').modal('show');
              return ;
            }
            var random_list = '';
            for(var item in json) {
              var tr = '<tr><td>' + item + '</td><td>' + json[item] + '</td></tr>';
              if(random_list == '') {
                random_list += item;
              } else {
                random_list += ',' + item;
              }
              $('#random_user_table').append(tr);
            }
            var random_export_btn = '<a id="random_export_btn" class="btn btn-success operate-user-btn" type="button">Export</a>';
            $('#generate_user_btn_modal').replaceWith(random_export_btn);
            $('#random_export_btn').click(function(){
              $('#random_user_table').tableExport({
                type: 'excel',
                escape: 'false',
                fileName: contestId + '_' + school
              })
            });
          },
          error: function() {
            $('#generate_user_modal').modal('hide');
            $('.hint-content').text("Contest ID doesn't exist");
            $('#hint').modal('show');
          }
        });
      }
    });
    /*delete-user*/
    $('#delete_user_btn_modal').click(function() {
      var $contestId = $('#delete_contest_id');
      var contestId = $contestId.val().trim();
      if(contestId == '') {
        $contestId.attr('placeholder', 'Required...');
        return ;
      } else {
        $(this).button('loading');
        $.ajax({
          url: '/dashboard/contest/randusers/' + contestId,
          type: 'DELETE',
          async: 'true',
          success: function() {
            $('#delete_user_modal').modal('hide');
            $('.hint-content').text('Delete Successful');
            $('#hint').modal('show');
          },
          error: function() {
            $('#delete_user_modal').modal('hide');
            $('.hint-content').text("Contest ID doesn't exist");
            $('#hint').modal('show');
          }
        });
      }

    });
  });
</script>
</body>
</html>
