@extends('layouts.base')
@section('content')

<div class="col-sm-9">
<br>
<h4><small>{{ $title }}</small></h4>

@foreach ($results as $post)
<div class="post">
  <hr>
  @if($post->flag)
  <h2 class="post_title"><a href="{{ url('post/'.$post->link) }}">{{ $post->title }}</a></h2>
  <h5><span class="glyphicon glyphicon-time"></span> Post by <a href="{{ url('panel/'.$post->user_id) }}">{{ $post->name }}</a>, {{ $post->updated_at }}.</h5>
  <p class="post_text">{{ $post->text }}</p>
  </p>  
  <br><br>
  @else
  <h2 class="post_title"><a href="{{ url('post/'.$post->link.'#'.$post->comm_id) }}">Reply on {{ $post->title }}</a></h2>
  <h5><span class="glyphicon glyphicon-time"></span> Post by <a href="{{ url('panel/'.$post->user_id) }}">{{ $post->name }}</a>, {{ $post->updated_at }}.</h5>
  <p class="post_text">{{ $post->text }}</p>
  </p>  
  <br><br>
  @endif
<div/>
@endforeach

<ul class="pager">
  @if ($results->currentPage() != 1) 
  <li><a href="{{ url($results->previousPageUrl()) }}">Previous</a></li>
  @endif
  
  @if ($results->hasMorePages())
  <li><a href="{{ url($results->nextPageUrl()) }}">Next</a></li>
  @endif
</ul>

@endsection