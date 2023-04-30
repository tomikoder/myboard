@extends('layouts.base')
@section('content')
@include('modals.private_message')

<br>
<h4><small>{{ $title }}</small></h4>
<h1>{{ $user->name }}</h1>
<h5>{{ $user->email }}</h5>
<h5>Total num of posts {{ $user->num_of_posts }}</h5>
<h5><a href="{{ url('posts/'.$user->id) }}">Show all posts</a></h5>
<br>
<br>
@if (!Auth::Guest())
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendmessagemodal" id="send_message">Send Message</button>
@endif
@endsection