@if (count($errors) > 0)
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>>
@endif
<form action = "/contest/{{$contest_id}}/register" method = "post">
{{csrf_field()}}
	<input type="text" name="captcha" class="form-control"  tabindex="3"/>
	<img src="{{ captcha_src("flat") }}" alt="Captcha" id="captcha"/>
	<input type = "submit" value = "register"/>
</form>
