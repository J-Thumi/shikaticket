@extends('errors.minimal')

@section('title', 'Server Error')
@section('code', '500')

@section('message')
    Something went wrong on our end while processing your request. Our technical team has been notified.
@endsection