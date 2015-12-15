@if(!isset($error))
    @foreach($infos as $info)
        <div>{{ $info }}</div>
    @endforeach
@else
    @foreach($errors as $error)
        <div>{{ $error }}</div>
    @endforeach
@endif
<form action="/dashboard/problem/{{ $problem->problem_id }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div>Edit Problem</div>
    <div>Problem Title</div>
    <input type="text" value="{{ $problem->title }}" name="title"/>
    <div>Problem Description</div>
    <textarea name="description">{{ $problem->description or "" }}</textarea>
    <div>Memory Limit</div>
    <input type="text" name="mem_limit" value="{{ $problem->mem_limit }}"/>
    <div>Time Limit</div>
    <input type="text" name="time_limit" value="{{ $problem->time_limit }}"/>
    <div>Output Limit</div>
    <input type="text" name="output_limit" value="{{ $problem->output_limit }}"/>
    <div>Input</div>
    <textarea name="input">{{ $problem->input or "" }}</textarea>
    <div>Output</div>
    <textarea name="output">{{ $problem->output or "" }}</textarea>
    <div>Sample Input</div>
    <textarea name="sample_input">{{ $problem->sample_input or ""}}</textarea>
    <div>Sample Output</div>
    <textarea name="sample_output">{{ $problem->sample_output or "" }}</textarea>
    <div>Source</div>
    <textarea name="source">{{ $problem->source or "" }}</textarea>
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

