@inject('roleCheck', 'App\Http\Controllers\RoleController')
<div id="dashboard-nav">
    <div class="text-center">
        <img class="img-circle" src="/avatar/{{Request::session()->get('uid')}}" alt="loading" />
        <p class="custom-word">{{Request::session()->get('username')}}</p>
        <hr />
    </div>
    <ul class="nav nav-stacked" id="dashboard-subnav">
        {{-- @if(session('uid') && session('uid') <=2) --}}
        <li><a href="/">NEUOJ</a></li>
        <li id="dashboard_profile"><a href="/dashboard/profile">Profile</a></li>
        @if($roleCheck->is("admin"))
            <li id="dashboard_problem"><a href="/dashboard/problem">Problem</a></li>
            <li id="dashboard_contest"><a href="/dashboard/contest">Contest</a></li>
            <li><a href="#">Submission</a></li>
            <li id="dashboard_system"><a href="/dashboard/system">SystemInfo</a></li>
            <li id="dashboard_training"><a href="/dashboard/training">Training</a></li>
            {{--<li class="separate-item"></li>--}}
            <li><a href="#">Executables</a></li>
            <li><a href="#">Language</a></li>
            <li><a href="#">Users</a></li>
        @endif
        <li id="dashboard_settings"><a href="/dashboard/settings">Settings</a></li>
    </ul>
</div>