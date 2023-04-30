@extends('layouts.base')
@section('content')
@include('modals.edit_post')

<div class="post">
  <hr>
  <h2 class="post_title"><a href="">{{ $post->title }}@if($post->is_closed)<bold>[CLOSED]</bold>@endif</a> 
  </h2>
  <h5><span class="glyphicon glyphicon-time"></span> Post by <a href="{{ url('panel/'.$post->user_id) }}">{{ $post->user->name }}</a>, {{ $post->updated_at }}.</h5>
  <p class="post_text">{{ $post->text }}</p>
  @if (Auth::user() && !$post->is_closed)
      @if (Auth::user()->id == $post->user->id)
      <p><a class="del_post" href="{{ url('delete/post'.$post->id) }}">delete&nbsp;&nbsp;</a>
      <a class=edit_post href="{{ url('edit/post'.$post->id) }}">edit</a>
      <a class= href=""><i class="fa-solid fa-arrow-up"></i>&nbsp;like  ({{ $post->num_of_likes }})</a>
      </p>
      @else
          @if ($post->you_like)
          <a class=like_post liked=1 href="{{ url('like/post'.$post->id) }}"><i class="fa-solid fa-arrow-down"></i>&nbsp;like  (<number>{{ $post->num_of_likes }}</number>)</a>
          @else
          <a class=like_post liked=0 href="{{ url('like/post'.$post->id) }}"><i class="fa-solid fa-arrow-up"></i>&nbsp;like  (<number>{{ $post->num_of_likes }}</number>)</a>
          @endif
      @endif
  @else
      <a class= href=""><i class="fa-solid fa-arrow-up"></i>&nbsp;like  ({{ $post->num_of_likes }})</a>
  @endif    
  <br><br>
<div/>

@if(!Auth::guest() && !$post->is_closed)
<h4>Leave a Comment:</h4>
      <form action="{{ url('comment/post'.$post->id) }}" id="commentform" method="POST" enctype="multipart/form-data">
        <div class="form-group">
          <textarea id="comm" class="form-control" rows="3" name="comm"></textarea>
        </div>
        <input type="submit" value="Submit">
      </form>
      <br><br>
@endif      
@php


function output_sub_comm($sub_comms, $post) {
    if (empty($sub_comms)) return;
    foreach ($sub_comms as $comment) {
       $url = url('panel/'.$comment->user_id);
       $url = "<a href='$url'>";  
       echo '<div class="col-xs-10">';
       if ($comment->created_at == $comment->updated_at) 
       {
       echo '<h4>'.$url.$comment->name.'</a>  <small>'.date('M d, Y, G:i', strtotime($comment->created_at)).'</small></h4>';
       }
       else 
       {
       echo '<h4>'.$url.$comment->name.'</a>  <small class="comment_date">'.date('M d, Y, G:i', strtotime($comment->created_at)).' Updated  at '.date('M d, Y, G:i', strtotime($comment->updated_at)).'</small></h4>'; 
       }
       echo '<p class="comment_text">'.$comment->text.'</p>';
       if (Auth::user() && !$post->is_closed)
       {
         if (Auth::user()->id == $comment->user_id) 
         {
          $url = url('delete/comment'.$comment->id);
          echo    "<small><a class='del_comment' href=$url>Delete</a>&nbsp;&nbsp";
          $url = url('edit/comment'.$comment->id);
          echo    "<a class='edit_comment' href=$url>Edit</a>";
          echo    '</small>';
          echo '<div class="for_edit_form">';
          echo "  <form action=$url class='comment_edit_form' method='POST' enctype='multipart/form-data'>";
          echo     '<div class="form-group">';
          echo        '<textarea class="form-control" rows="3" name="comm"></textarea>';
          echo     '</div>';
          echo     '<input type="submit" value="Submit">';
          echo   '</form>';
          echo '</div>'; 
         }
         else 
         {
          echo    '<small><a class="reply_comment" href="">Reply</a></small>';
          echo    '<br>';
          $url = url('comment'.$comment->id.'/post'.$post->id);
          echo    "<form action=$url class='comment_reply_form' method='POST' enctype='multipart/form-data'>";
          echo     '<div class="form-group">';
          echo        '<textarea class="form-control sub_comm" rows="3" name="comm"></textarea>';
          echo     '</div>';
          echo     '<input type="submit" value="Submit">';
          echo   '</form>'; 
         }
       }
       output_sub_comm($comment->sub_comm, $post);  
       echo '</div>';
    }
  }

@endphp

<h1>answers</h1>
<div class="row" id="comments">
<hr>
@foreach ($comments as $comment) 
<div class="col-sm-10 main_comm" id="{{ $comment->id }}">
     @if ($comment->created_at == $comment->updated_at) 
          <h4><a href="{{ url('panel/'.$comment->user_id) }}">{{ $comment->name }}</a>  <small class="comment_date">{{ date('M d, Y, G:i', strtotime($comment->created_at)) }}</small></h4>
     @else
          <h4><a href="{{ url('panel/'.$comment->user_id) }}">{{ $comment->name }}</a>  <small class="comment_date">{{ date('M d, Y, G:i', strtotime($comment->created_at)) }} Updated  at {{ date('M d, Y, G:i', strtotime($comment->updated_at)) }}</small></h4>
     @endif
          <p class="comment_text">{{ $comment->text }}</p>
     @if (Auth::user() && !$post->is_closed)
     @if (Auth::user()->id == $comment->user_id)
        <small>
          <a class="del_comment" href="{{ url('delete/comment'.$comment->id) }}">Delete</a>&nbsp;&nbsp
          <a class="edit_comment" href="{{ url('edit/comment'.$comment->id) }}">Edit</a>
        </small>
        <div class="for_edit_form">
          <form action="{{ url('edit/comment'.$comment->id) }}" class="comment_edit_form" method="POST" enctype="multipart/form-data">
            <div class="form-group">
            <textarea class="form-control" rows="3" name="comm"></textarea>
            </div>
            <input type="submit" value="Submit">
          </form>
        </div>
      @else
        <small>
          <a class="reply_comment" href="">Reply</a>
          <br>
        </small>
        <form action="{{ url('comment'.$comment->id.'/post'.$post->id) }}" class="comment_reply_form" method="POST" enctype="multipart/form-data">
          <div class="form-group">
            <textarea class="form-control sub_comm" rows="3" name="comm"></textarea>
          </div>
          <input type="submit" value="Submit">
        </form>
      @endif
      @endif
      {{ output_sub_comm($comment->sub_comm, $post) }}
</div>
@endforeach
</div>
@endsection