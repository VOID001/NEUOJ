@inject('roleCheck', 'App\Http\Controllers\RoleController')
<!doctype html>
<html>
<head>
    <title>Contest List</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <link rel="stylesheet" href="/css/contest.css">
    <script src="/js/extendPagination.js"></script>
    <script type="text/javascript">
        $(function(){
            $("#contest").addClass("active");
        })
        $(document).ready(function(){
            var targetHerf = "/contest/p/";
            $('#callBackPager').extendPagination({
                totalPage : {{ $page_num }},
                showPage : 5,
                pageNumber : {{ $page_id }}
            },targetHerf);
        });
    </script>
</head>
<body class="home_body">
@include("layout.header")

    <h3 class="text-center">Contest List</h3>
    <div class="main">
    <table class="table table-striped table-bordered table-hover contest_list contest_table" width="100%" >
        <thead>
            <th class="text-center"id="contest_id">
                Contest ID
            </th>
            <th class="text-center" id="contest_name">
                Contest Title
            </th>
            <th class="text-center"id="contest_begin_time">
                Start Time
            </th>
            <th class="text-center" id="contest_type">
                Type
            </th>
            <th class="text-center" id="contest_status">
                Status
            </th>
            @if($roleCheck->is('admin'))
                <th class="text-center"id="contest_management">
                    Management
                </th>
            @endif
        </thead>
        @if(isset($contests))
            @foreach($contests as $contest)
                <tr class="table_row">
                    <td>
						<a href="/contest/{{ $contest->contest_id }}" class="text-center table_row_td">
                            {{ $contest->contest_id }}
						</a>
                    </td>
                    <td id="contest_name_el">
						<a href="/contest/{{ $contest->contest_id }}" class="text-center table_row_td">
                            <nobr>&nbsp;{{ $contest->contest_name }}</nobr>
						</a>
                    </td>
                    <td>
						<a href="/contest/{{ $contest->contest_id }}" class="text-center table_row_td">
                        {{ $contest->begin_time }}
						</a>
                    </td>
                    <td class="text-center">
                        @if($contest->contest_type == 0)
                            Public
                        @elseif($contest->contest_type == 1)
                            Private
                        @elseif($contest->contest_type == 2)
                            Register
                        @endif
                    </td>
                    <td class="text-center">
                        @if($contest->status=="Running")
                            <span class="badge contest_list_status_running">{{ $contest->status }}</span>
                        @elseif($contest->status=="Ended")
                            <span class="badge contest_list_status_ended">{{ $contest->status }}</span>
                        @else
                            <span class="badge contest_list_status_ended">{{ $contest->status }}</span>
                        @endif
                    </td>
                    @if($roleCheck->is('admin'))
                        <td class="text-center">
                            <a class="btn btn-default" href="/dashboard/contest/{{ $contest->contest_id }}">manage</a>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif

    </table>
    </div>
    <div class="text-center" id="callBackPager"></div>
    <div style="padding-bottom: 40px">
@include("layout.footer")
</body>
</html>
