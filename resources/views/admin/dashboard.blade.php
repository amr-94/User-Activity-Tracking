@extends('layouts.admin')

@section('content')
    <h3>Dashboard</h3>
    <div class="row">
        <div class="col-md-4">
            <div class="p-3 card">
                <h5>Total Users: {{ $users_count ?? 0 }}</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 card">
                <h5>Total Admins: {{ $admins_count ?? 0 }}</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 card">
                <h5>Idle Sessions: {{ $idle_count ?? 0 }}</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 card">
                <h5>Penalties: {{ $penalty_count ?? 0 }}</h5>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 card">
                <h5>Activities Logged: {{ $activities_count ?? 0 }}</h5>
            </div>
        </div>



    </div>

    <hr>
    <h4>Latest Activities</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Model</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($latest_activities as $a)
                <tr>
                    <td>{{ $a->user->name }}</td>
                    <td>{{ $a->action }}</td>
                    <td>{{ $a->model_type }}</td>
                    <td>{{ $a->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
