<!doctype html>
<html>
<head>
    <title>Problem</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
    <script>
        $(function(){
            $("#submit").click(function(){
                $("#mymodal").modal("toggle");
            });
        });
    </script>
</head>
<body>
    @include("layout.header")
    <h3 class="text-center">Problem: {{ $title }}</h3>
    <div class="text-center text-primary">Time limit: {{ $time_limit }}s&nbsp;&nbsp;&nbsp;&nbsp;Mem limit:{{ $mem_limit }}K @if($is_spj == 1) <b>Special Judge</b>@endif</div>
    <div class="panel panel-default main">

        <h3>Problem Description</h3>
        <div>{{ $description }}</div>
        <hr>
        <h3>Input</h3>
        <div>123</div>
        <hr>
        <h3>Output</h3>
        <div>123</div>
        <hr>
        <h3>Sample Input</h3>
        <div>123</div>
        <hr>
        <h3>Sample Output</h3>
        <div>123</div>
        <hr>
        <h3>Source</h3>
        <div>admin</div>
    </div>
    @if(Request::session()->get('username') != NULL)
        <div class="text-center" style="padding-bottom: 50px"><a class="btn btn-success" id="submit">submit</a></div>
    @else
        <div class="text-center" style="padding-bottom: 50px"><a href="/auth/signin">Sign in</a> to Submit your code</div>
    @endif
    @if(isset($errors))
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    @endif

    <div class="modal fade" id="mymodal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #286090">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"style="color: white">Submit</h4>
                </div>
                <div class="modal-body">

                    <form action="/submit/{{ $problem_id }}" method ="POST">
                     {{ csrf_field() }}
                        <span name="Language" style="float: left;font-size: 16px;margin-top: 8px">language:</span>
                        <select name="lang" class="form-control" style="display: inline-block;width: 100px;margin-bottom: 10px">
                            <option name="c">C</option>
                            <option name="java">Java</option>
                            <option name="cpp">C++</option>
                            <option name="cpp11">C++11</option>
                        </select>
                        <textarea name="code" id="promblem_submit_textarea" class="form-control" placeholder="Input your code here..."></textarea>

                        <input type="reset" class="btn btn-primary pull-right" value="&nbsp;Reset&nbsp;" style="margin-left: 10px;margin-top: 10px"/>
                        <input type="submit" class="btn btn-primary pull-right" value="Submit" style="margin-top: 10px"/>


                        <div class="text-center" style="padding-bottom: 50px;"></div>
                    </form>

                </div>
            </div>
        </div>
    </div>

        @include("layout.footer")
</body>
<html>
