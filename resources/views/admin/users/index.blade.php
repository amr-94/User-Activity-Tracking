@extends('layouts.admin')

@section('content')
    <h3>All Users</h3>

    <a href="{{ route('admin.users.create') }}" class="mb-3 btn btn-primary">Add User</a>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Avatar</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="usersTable">
            @foreach ($users as $user)
                <tr data-id="{{ $user->id }}">
                    <td>{{ $user?->id }}</td>
                    <td>{{ $user?->name }}</td>
                    <td>{{ $user?->email }}</td>
                    <td>{{ $user?->role }}</td>
                    <td>
                        @if ($user->avatar_path)
                            <img src="{{ asset('storage/' . $user->avatar_path) }}" width="40">
                        @else
                            <span class="text-muted">No Avatar</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-info">Show</a>
                        <button class="btn btn-sm btn-danger delete-btn">Delete</button>

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(function() {
                $('.delete-btn').click(function() {
                    if (!confirm('Delete user?')) return;
                    const row = $(this).closest('tr');
                    const id = row.data('id');

                    $.ajax({
                        url: `/admin/users/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: () => row.remove(),
                        error: () => alert('Error deleting user')
                    });
                });
            });
        </script>
    @endpush
@endsection
