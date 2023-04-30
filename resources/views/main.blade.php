@extends('layouts.base')
@section('content')
@include('modals.edit_post')

<div class="col-sm-9">
<br>
<h4><small>RECENT POSTS</small></h4>
@foreach ($posts_pag as $post)
<div class="post">
  <hr>
  <h2 class="post_title"><a href="{{ url('post/'.$post->link) }}">{{ $post->title }}</a></h2>
  <h5><span class="glyphicon glyphicon-time"></span> Post by <a href="{{ url('panel/'.$post->user_id) }}">{{ $post->name }}</a>, {{ $post->updated_at }}.</h5>
  <p class="post_text">{{ $post->text }}</p>
  <p><a href="{{ url('post/'.$post->link) }}">comments({{ $post->num_of_comm }})</a>
    @if (Auth::user()) 
      @if (Auth::user()->id == $post->user_id)
      <a class="del_post" href="{{ url('delete/post'.$post->post_id) }}">delete</a>
      <a class=edit_post href="{{ url('edit/post'.$post->post_id) }}">edit</a>
      <a class= href=""><i class="fa-solid fa-arrow-up"></i>&nbsp;like  ({{ $post->num_of_likes }})</a>
      @else
        @if ($post->you_like)
        <a class=like_post liked=1 href="{{ url('like/post'.$post->post_id) }}"><i class="fa-solid fa-arrow-down"></i>&nbsp;like  (<number>{{ $post->num_of_likes }}</number>)</a>
        @else
        <a class=like_post liked=0 href="{{ url('like/post'.$post->post_id) }}"><i class="fa-solid fa-arrow-up"></i>&nbsp;like  (<number>{{ $post->num_of_likes }}</number>)</a>
        @endif
      @endif
    @else
      <a class=like_post href="{{ url('like/post'.$post->post_id) }}"><i class="fa-solid fa-arrow-up"></i>&nbsp;like  ({{ $post->num_of_likes }})</a>
    @endif
  </p>  
  <br><br>
<div/>
@endforeach

<ul class="pager">
  @if ($posts_pag->currentPage() != 1) 
  <li><a href="{{ url($posts_pag->previousPageUrl()) }}">Previous</a></li>
  @endif
  
  @if ($posts_pag->hasMorePages())
  <li><a href="{{ url($posts_pag->nextPageUrl()) }}">Next</a></li>
  @endif
</ul>
@endsection