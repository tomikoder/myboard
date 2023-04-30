<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ $title }}</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="{{ URL::asset('js/secret_data.js') }}"></script>
  <script src="{{ URL::asset('js/js.cookie.js') }}"></script>
  <script src="{{ URL::asset('js/main.js') }}"></script>
  <script src="https://kit.fontawesome.com/d34d47591f.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href={{ URL::asset('css/main.css') }}>
  <script src="https://kit.fontawesome.com/d34d47591f.js" crossorigin="anonymous"></script>
  <link rel="stylesheet" type="text/css" href={{ URL::asset('css/main.css') }}>

</head>
<body>

@include('modals.login')
@include('modals.register')
@include('modals.post')
@include('modals.loged_users')
@include('modals.forget_modal')

<script>var BD = {!! json_encode($user_data, JSON_HEX_TAG) !!};</script>


<div class="container-fluid">
  <div class="row content">
    <div class="col-sm-3 sidenav">
      <h3>My Board</h3>
      <br>
      <br>
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="/">Home</a></li>
        <li><a id="notifys" href="{{ route('notify') }}">Notifys @if(!Auth::guest())<span class="badge badge-danger"><number>{{ $user_data['notify_count'] }}</number></span>@endif</a></li>
        <li><a id="messages" href="{{ route('your_messages') }}">Your Messages</a></li>
        @if (!Auth::guest())
        <li><a id="your_posts" href="{{ url('posts/'.Auth::user()->id) }}">Your Posts</a></li>
        @else
        <li><a id="your_posts" href="">Your Posts</a></li>
        @endif
        @if(!Auth::guest())<li><a href="" data-toggle="modal" data-target="#loged_users">Online loged users <span class="badge badge-danger"><number id="online_users">0</number></span></a></li>@endif
      </ul><br>
      <div class="input-group">
        <input type="text" class="form-control" placeholder="Search Forum.." id="search_input">
        <span class="input-group-btn">
          <button class="btn btn-default" type="button" id="search_button">
            <span class="glyphicon glyphicon-search"></span>
          </button>
        </span>
      </div>

      <br>
      @if (Auth::guest())
      <ul class="nav nav-pills nav-stacked">
        <li><a href="" id="login"  data-toggle="modal" data-target="#loginmodal">Login</a></li>
        <li><a href="" id="signup" data-toggle="modal" data-target="#signupmodal">Sign up</a></li>
      </ul> 
      @else
        <h4>Hello {{ Auth::user()->name }} </h4> 
        <ul class="nav nav-pills nav-stacked">
        <li><a href="{{ route('private_panel') }}">Your account</a></li>  
        <li><a href="{{ url('logout') }}" id="logout">Logout</a></li>
        <li><a href="" id="post" data-toggle="modal" data-target="#addpost">+ Add new post</a></li>
      </ul> 

      @endif  
       
    </div>
  <div class="col-sm-9">

  @yield('content')
  <div>

</body>
</html>
