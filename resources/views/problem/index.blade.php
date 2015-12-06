<!doctype html>
<html>
<head>
    <title>Problem</title>
    @include("layout.head")
    <link rel="stylesheet" href="/css/main.css">
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
        <div class="text-center" style="padding-bottom: 50px"><a href="/submit/{{ $problem_id }}" class="btn btn-success">submit</a></div>
        <div>You are logged in, you can submit the code</div>
        <form action="/submit/{{ $problem_id }}" method ="POST">
            {{ csrf_field() }}
            <table>
                <tr>
                    <label name="lang">Select language</label>
                    <select name="Language">
                        <option name="c">C</option>
                        <option name="java">Java</option>
                        <option name="cpp">C++</option>
                        <option name="cpp11">C++11</option>
                    </select>
                </tr>
                <tr>
                    <td>
                        <input type=""/>
                    </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit" value="Submit Code"/>
                    </td>
                    <td>
                        <input type="reset" value="Reset"/>
                    </td>
                </tr>
        </table>
            <div class="text-center" style="padding-bottom: 50px"></div>
    </form>

        @else
            <div class="text-center" style="padding-bottom: 50px">Sign in to Submit your code</div>
        @endif

    @include("layout.footer")
</body>
<html>

