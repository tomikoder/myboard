@extends('layouts.base')
@section('content')
@include('modals.private_message_reply')

@php

function traverse_replys($curr_comm) {
    if (!$curr_comm) return;
    echo '<div class="message">';  
    echo    '<hr>';
    echo    '<h5><span class="glyphicon glyphicon-time"></span> From  '.$curr_comm["from"].'  <small>'.$curr_comm["date"].'</small>.</h5>';
    echo    '<p class="post_text">'.$curr_comm["text"].'</p>'; 
    echo    '<br><br>';
    echo '</div>';
    traverse_replys($curr_comm['response']);
}



@endphp

<div id ="msgs">
  <div class="message">
    <hr>
    <h5><span class="glyphicon glyphicon-time"></span> From {{ $msg->body['from'] }} <small>{{ ''.date('M d, Y, G:i', strtotime($msg->updated_at)) }}</small>.</h5>  
    <p class="post_text">{{ $msg->body['text'] }}</p>
    <br><br>
  </div>
  {{ traverse_replys($msg->response) }}
</div>
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reply_modal">Reply</button>
<script src="{{ URL::asset('js/message.js') }}"></script>
@endsection