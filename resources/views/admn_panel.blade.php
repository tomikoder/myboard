@extends('layouts.base')
@section('content')
<h1>Hello admin</h1>

<form action="{{ route('admin_panel_users') }}" method="POST">
  <label for="users">Users:</label>
  <select name="users" id="users">
    @foreach($users as $user)
        @if($user->is_baned) <option value="{{ $user->id }}">BANED {{ $user->name }}</option>
        @elseif(!$user->is_active) <option value="{{ $user->id }}">INACTIVE {{ $user->name }}</option>
        @else <option value="{{ $user->id }}">{{ $user->name }}</option>
        @endif 
    @endforeach
  </select>
  <select name="action">
    <option value="delete">Delete</option>
    <option value="add_ban">Add ban</option>
    <option value="remove_ban">Remove ban</option>
    <option value="add_again">Add again</option>

  </select>
  <br><br>
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
  <input type="submit" value="Submit">
</form>

<br><br>

<form action="{{ route('admin_panel_posts') }}" method="POST">
  <label for="posts">Posts:</label>
  <select name="posts" id="posts">
    @foreach($posts as $post)
        @if($post->is_closed)<option value="{{ $post->id }} {{ $post->user_id }}">[CLOSED]{{ $post->title }}</option>
        @else <option value="{{ $post->id }} {{ $post->user_id }}">{{ $post->title }}</option>
        @endif 
    @endforeach
  </select>
  <select name="action">
    <option value="delete">Delete</option>
    <option value="close">Close</option>
  </select>
  <br><br>
  <input type="hidden" name="_token" value="{{ csrf_token() }}" />
  <input type="submit" value="Submit">
</form>

@endsection('content')
