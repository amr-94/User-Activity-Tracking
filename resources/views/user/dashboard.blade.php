@extends('layouts.admin')

@section('content')
    <h3>Welcome, {{ auth()->user()->name }}</h3>
    <p>This is your user dashboard.</p>

    <div class="alert alert-info">
        You are logged in as a normal user.
    </div>

    <div id="idle-status" class="mt-3"></div>
@endsection
