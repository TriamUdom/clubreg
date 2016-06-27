@extends('layout.main')

@section('content')
    @foreach($messages as $message)
        {{!! $message !!}}
    @endforeach
    @foreach($required_audition as $bit)
        {{ $bit }}
    @endforeach
@stop
