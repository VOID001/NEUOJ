@include('layout.head')
@if(isset($errors))
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
@if(isset($info))
    <div>{{ $info }}</div>
@endif
<form action="/auth/request" method="POST">
    <div>
        <label>Email</label><input name="email" type="text"/>
    </div>
    <div>
        <label>Captcha</label><input name="captcha" type="text"/>
        <img src="{{ captcha_src("flat") }}" alt="Captcha" id="captcha"/>
    </div>
    {{ csrf_field() }}
    <input type="submit" value="Reset"/>
</form>
<div>
    If you do not found email in 5 minutes, check for spam list
</div>
<script>
    $(document).ready(function(){
        $('#captcha').click(function(){
            this.src=this.src+'?'+new Date();
        });
    });
</script>
