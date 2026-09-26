@extends('errors.minimal')

@section('title', 'Session Expired')
@section('code', '419')

@section('message')
    Your session has timed out due to inactivity. Please refresh the page or try submitting the form again.
@endsection