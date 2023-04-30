@extends('layouts.base')
@section('content')
<br>
<h4><small>&nbsp;&nbsp;{{ $title }}</small></h4>

@foreach ($results as $notify)

@if($notify['type'] == 'Post comment' || $notify['type'] == 'Comment comment')
    @if(!$notify['readed'])
    <div class="alert alert-success">
        <bold>{{ $notify['created_at'] }}</bold>&nbsp;&nbsp;
        <a href="{{ url('post/'.$notify['data']['post_link'].'#'.$notify['data']['comment_id']) }}"><strong>Your post was comment by  {{ $notify['data']['user_name'] }}</strong></a>   
    </div>
    @else
    <div class="alert alert-light">
        <bold>{{ $notify['created_at'] }}</bold>&nbsp;&nbsp;
        <a href="{{ url('post/'.$notify['data']['post_link'].'#'.$notify['data']['comment_id']) }}"><strong>Your post was comment by  {{ $notify['data']['user_name'] }}</strong></a>   
    </div>
    @endif
@elseif($notify['type'] == 'New Message')
    @if(!$notify['readed'])
    <div class="alert alert-success">
        <bold>{{ $notify['created_at'] }}</bold>&nbsp;&nbsp;
        <a href="{{ url('read/message/'.$notify['data']['msg_link']) }}"><strong>Your have message from  {{ $notify['data']['user_name'] }}</strong></a>   
    </div>
    @else
    <div class="alert alert-light">
        <bold>{{ $notify['created_at'] }}</bold>&nbsp;&nbsp;
        <a href="{{ url('read/message/'.$notify['data']['msg_link']) }}"><strong>Your have message from  {{ $notify['data']['user_name'] }}</strong></a>   
    </div>
    @endif

@endif

@endforeach
@endsection