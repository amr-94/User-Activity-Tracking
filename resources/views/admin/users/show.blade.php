@extends('layouts.admin')

@section('content')
    <div class="container">
        <h3>User Details - {{ $user->name }}</h3>

        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i> User Details - {{ $user->name }}
                </h4>
                <span class="badge bg-light text-dark">{{ $user->role_name }}</span>
            </div>

            <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between p-4">
                <div class="mb-3 mb-md-0 text-center text-md-start">
                    <p><strong>ID:</strong> {{ $user->id }}</p>
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Role:</strong> {{ $user->role_name }}</p>
                    <p><strong>Created At:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
                </div>

                <div class="text-center">
                    @if ($user->avatar_path)
                        <img src="{{ asset('storage/' . $user->avatar_path) }}" alt="{{ $user->name }}"
                            class="rounded-circle shadow" width="150" height="150" style="object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow"
                            style="width: 150px; height: 150px;">
                            <i class="fas fa-user text-secondary fs-1"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>



        <ul class="mb-3 nav nav-tabs" id="userTabs" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#activities">Activities</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#penalties">Penalties</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#idleSessions">Idle Sessions</a></li>
        </ul>

        <div class="tab-content">
            {{-- Activities --}}
            <div class="tab-pane fade show active" id="activities">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <h5>User Activities</h5>
                    <button class="btn btn-danger btn-sm delete-all" data-type="activities">Delete All</button>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Model</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->activityLogs as $activity)
                            <tr>
                                <td>{{ $activity->action }}</td>
                                <td>{{ $activity->model }}</td>
                                <td>{{ $activity->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No activities</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Penalties --}}
            <div class="tab-pane fade" id="penalties">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <h5>User Penalties</h5>
                    <button class="btn btn-danger btn-sm delete-all" data-type="penalties">Delete All</button>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th>Count</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->penalties as $penalty)
                            <tr>
                                <td>{{ $penalty->reason }}</td>
                                <td>{{ $penalty->count }}</td>
                                <td>{{ $penalty->date->format('Y-m-d') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No penalties</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Idle Sessions --}}
            <div class="tab-pane fade" id="idleSessions">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <h5>Idle Sessions</h5>
                    <button class="btn btn-danger btn-sm delete-all" data-type="idle-sessions">Delete All</button>
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->idleSessions as $idle)
                            <tr>
                                <td>{{ $idle->start_time }}</td>
                                <td>{{ $idle->end_time ?? '-' }}</td>
                                <td>{{ ucfirst($idle->status) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No idle sessions</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(function() {
                $('.delete-all').click(function() {
                    const type = $(this).data('type');
                    if (!confirm(`Delete all ${type.replace('-', ' ')} for this user?`)) return;

                    $.ajax({
                        url: `/admin/users/{{ $user->id }}/${type}`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: res => {
                            alert(res.message);
                            location.reload();
                        },
                        error: err => {
                            console.error(err);
                            alert('Error deleting records.');
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection
