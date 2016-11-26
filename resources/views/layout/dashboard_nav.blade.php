@inject('roleCheck', 'App\Http\Controllers\RoleController')
<div id="dashboard-sidenav">
  <div class="text-center">
    <img id="dashboard-profile-img" class="img-circle" src="/avatar/{{Request::session()->get('uid')}}" alt="loading"/>
    <p class="custom-word">{{Request::session()->get('username')}}</p>
    <hr/>
  </div>
  <ul class="nav nav-stacked navigation" id="dashboard-subnav">
    {{-- @if(session('uid') && session('uid') <=2) --}}
    <ul class="dashboard-sidenav-mainmenu">
      <li><a href="/">NEUOJ</a></li>
      <li id="dashboard_profile"><a href="/dashboard/profile"><span class="glyphicon glyphicon-user"
                                                                    aria-hidden="true"></span>Profile</a></li>
      @if($roleCheck->is("admin"))
        <li><a href="#"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>Management<span
                class="glyphicon glyphicon-chevron-down" style="float:right"></span></a>
          <ul class="submenu">
            <li id="dashboard_problem"><a href="/dashboard/problem">Problem</a></li>
            <li id="dashboard_contest"><a href="/dashboard/contest">Contest</a></li>
            <li id="dashboard_submisson"><a href="#">Submission</a></li>
            <li id="dashboard_training"><a href="/dashboard/training">Training</a></li>
            <li id="judgehost_manage"><a href="/dashboard/judgehost">Judgehost</a></li>
          </ul>
        </li>
        {{--<li class="separate-item"></li>--}}
        <li id="dashboard_executable"><a href="#"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span>Executables</a>
        </li>
        <li><a href="#"><span class="glyphicon glyphicon-font" aria-hidden="true"></span>Language</a></li>
        <li id="dashboard_system"><a href="/dashboard/system"><span class="glyphicon glyphicon-link"
                                                                    aria-hidden="true"></span>SystemInfo</a></li>

        <li><a id="dashboard_users" href="/dashboard/users"><span class="glyphicon glyphicon-user"
                                                                  aria-hidden="true"></span>Users</a></li>
      @endif
      <li id="dashboard_settings"><a href="/dashboard/settings"><span class="glyphicon glyphicon-edit"
                                                                      aria-hidden="true"></span>Settings</a></li>
    </ul>
  </ul>
</div>
<script>
  $(document).ready(function () {
    var showBtn = $("ul > li > ul");
    showBtn.hide();
    $('ul>li>a').click(function () {
      $(this).next('ul').toggle();
    });
  })
</script>
