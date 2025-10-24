@extends('layouts.admin')

@section('content')
    <h3>Create User</h3>

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Name</label>
            <input name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input name="email" type="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input name="password_confirmation" type="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Avatar</label>
            <input type="file" name="avatar" class="form-control">
        </div>
        <div class="mb-3">
            <label>Role</label>
            <select name="role" class="form-control">
                <option value="1">User</option>
                <option value="2">Admin</option>
            </select>
        </div>
        <button class="btn btn-success">Save</button>
    </form>
@endsection
