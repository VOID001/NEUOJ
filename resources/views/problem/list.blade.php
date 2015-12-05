<!doctype html>
<html>
<head>
    <title>Problem List</title>
    <?php require("./UI/head.php");?>
    <link rel="stylesheet" href="/css/main.css">
    <script type="text/javascript">
        $(function(){
            $("#problem").addClass("active");
        })
    </script>
</head>
<body>
    <?php require("./UI/header.php");?>

    <h3 class="text-center">Problem List</h3>
    <div class="main">
        <form class="form-inline">
            <span style="font-size: 18px">Search:</span><input class="form-control" style="width: 200px;" aria-controls="problemset" placeholder="请输入关键字" type="text">
            <input type="submit" class="btn btn-info" value="Go">
        </form>

        <table class="table table-striped table-bordered table-hover" id="problemlist" width="100%" style="margin-top: 5px">
            <thead>
                <tr>
                    <th class="text-center">Problem ID</th>
                    <th class="text-left">Title</th>
                    <th class="text-center">Difficulty</th>
                    <th class="text-center">AC/Submit</th>
                    <th class="text-center">Author</th>
                    <th class="text-center">Visibility_Lock(use for debug version)</th>
                </tr>
            </thead>
            @if($problems != NULL)
                @foreach($problems as $problem)
                    <tr>
                        <td class="text-center"><a href="/problem/{{ $problem->problem_id }}">{{ $problem->problem_id }}</a></td>
                        <td class="text-left">{{ $problem->title }}</td>
                        <td class="text-center">{{ $problem->difficulty }}</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">{{ $problem->visibility_locks }}</td>
                    </tr>
                @endforeach
            @endif

            <!--only for test-->
            @if($problems != NULL)
                @foreach($problems as $problem)
                    <tr>
                        <td class="text-center"><a href="/problem/{{ $problem->problem_id }}">{{ $problem->problem_id }}</a></td>
                        <td class="text-left">{{ $problem->title }}</td>
                        <td class="text-center">{{ $problem->difficulty }}</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">&nbsp;</td>
                        <td class="text-center">{{ $problem->visibility_locks }}</td>
                    </tr>
                @endforeach
            @endif
            </table>


        <ul class="pager" role="fanye">
            @if(!isset($firstPage))
                 <li ><a href="/problem/p/{{ $page_id - 1 }}">&laquo;Previous</a></li>
            @endif
            @if(!isset($lastPage))
                <li><a href="/problem/p/{{ $page_id + 1 }}">&nbsp;&nbsp;&nbsp;Next&nbsp;&nbsp;&nbsp;&raquo;</a></li>
            @endif
        </ul>

        <div>

        </div>
    </div>

        <form action="{{ Request::getUri() }}" method="POST" class="text-center" style="padding-bottom: 40px">
            {{ csrf_field() }}
            <label>How many problems to show on one page </label>
            <input type="text" value="{{ $problemPerPage }}" name="problem_per_page"/>
            <input type="submit" value="show"/>
        </form>

    <?php  require("./UI/footer.php");?>
</body>
</html>