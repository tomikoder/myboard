@extends('layouts.base')
@section('content')
@if($user->is_super_user) <a href="{{ route('admin_panel') }}"><button type="button" class="btn btn-primary">Admin Panel</button></a>
@endif
<h1>Hello {{ $user->name }} </h1>
<h4>Number of your posts {{ $user->num_of_posts }}</h4>
<br>
<h1> Change password</h1>
<div id="change_pass_errors">
  
</div>
<p style="color:red;" id="change_password_err"></p>
<form id="change_pass_form" action="{{ route('change_pass') }}" method="POST" class="form-inline">
<div class="form-group">
  <label for="old_pass">Old password: </label><br>
  <input type="password" id="old_pass" name="old_pass" class="form-control"><br>
  <label for="new_pass">New password: </label><br>
  <input type="password" id="new_pass" name="new_pass" class="form-control"><br>
  <label for="new_pass_retry">New password again: </label><br>
  <input type="password" id="new_pass_retry" name="new_pass_retry" class="form-control"><br>
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
  <input type="submit" value="Submit">
</div>  
</form>
<br><br>

<h4>Remove Account</h4>
<form id="remove_account" action="{{ route('remove_account') }}" method="POST" class="form-inline">
  <label for="password">Password: </label><br>
  <input type="password" id="password" name="password" class="form-control"><br>
  <input type="submit" value="Submit">
</form>



@endsection