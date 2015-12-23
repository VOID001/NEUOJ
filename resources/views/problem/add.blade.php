@if(!isset($error))
    @foreach($infos as $info)
        <div>{{ $info }}</div>
    @endforeach
@else
    @foreach($errors as $error)
        <div>{{ $error }}</div>
    @endforeach
@endif
<form action="/dashboard/problem/add" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div>Add Problem</div>
    <div>Problem Title</div>
    <input type="text" value="" name="title"/>
    <div>Problem Description</div>
    <textarea name="description"></textarea>
    <div>Memory Limit</div>
    <input type="text" name="mem_limit" value="65536"/>
    <div>Time Limit</div>
    <input type="text" name="time_limit" value="1000"/>
    <div>Output Limit</div>
    <input type="text" name="output_limit" value="5120"/>
    <div>Input</div>
    <textarea name="input"></textarea>
    <div>Output</div>
    <textarea name="output"></textarea>
    <div>Sample Input</div>
    <textarea name="sample_input"></textarea>
    <div>Sample Output</div>
    <textarea name="sample_output"></textarea>
    <div>Source</div>
    <textarea name="source"></textarea>
    <!-- Now only support single testcase -->
    @if($testcases == NULL)
        <div>You do not have any testcase here</div>
        <div>Upload Input File</div>
        <input type="file" name="input_file[]"/>
        <div>Upload Output File</div>
        <input type="file" name="output_file[]"/>
    @else
        @foreach($testcases as $testcase)
            <div>Upload Input File</div>
            <input type="file" name="input_file[]"/>
            <div>Upload Output File</div>
            <input type="file" name="output_file[]"/>
        @endforeach
    @endif
    <div>
        <input type="submit" value="Save"/>
    </div>
</form>

