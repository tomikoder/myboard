@extends('layouts.base')
@section('content')
@foreach ($data as $msg)
<div class="alert alert-light">
        <bold>{{ $msg['created_at'] }}</bold>&nbsp;&nbsp;
        <a href="{{ url('read/message/'.$msg['link']) }}"><strong>Your message to  {{ $msg['body']['to'] }}</strong></a>   
</div>
@endforeach
@endsection