@extends('layouts.admin')

@section('title', 'Activity Logs')

@section('content')
<div class="mb-4 d-flex justify-content-between align-items-center">
    <h3>Activity Logs</h3>

    <div>
        <form class="gap-2 d-flex" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Search activities..."
                value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Action</th>
                        <th>Model</th>
                        <th>Time</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>{{ $activity->user->name }}</td>
                        <td>{{ $activity->action }}</td>
                        <td>{{ $activity->model_type }}</td>
                        <td>{{ $activity->created_at->diffForHumans() }}</td>
                        <td>
                            <form action="{{ route('admin.activities.destroy', $activity->id) }}"
                                method="POST"
                                class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center">No activities found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $activities->appends(request()->query())->links() }}
        </div>
    </div>
</div>
@endsection