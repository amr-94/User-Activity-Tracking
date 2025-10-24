@extends('layouts.admin')

@section('content')
    <h3>Edit User</h3>

    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" value="{{ $user->email }}" required>
        </div>
        <div class="mb-3">
            <label>New Password (optional)</label>
            <input name="password" type="password" class="form-control">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="1" @selected($user->role == 1)>User</option>
                <option value="2" @selected($user->role == 2)>Admin</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Avatar</label>
            <input type="file" name="avatar" class="form-control">
            @if ($user->avatar_path)
                <img src="{{ asset('storage/' . $user->avatar_path) }}" width="60" class="mt-2">
            @endif
        </div>

        <button class="btn btn-success">Update</button>
    </form>
@endsection
