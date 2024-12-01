@extends('layout')

@section('title')
    Create Job
@endsection

@section('content')
    <h1>Create New Job</h1>
    <form action="/jobs" method="POST">
        @csrf
        <input type="text" name="title" id="" placeholder="title">
        <input type="text" name="description" id="" placeholder="description">
        <button type="submit">Submit</button>
    </form>
@endsection
