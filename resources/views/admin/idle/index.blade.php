@extends('layouts.admin')

@section('admin-content')
    <h3>Idle Sessions</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Level</th>
                <th>Start</th>
                <th>End</th>
                <th>Duration (mins)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="idleTable">
            @foreach ($sessions as $s)
                <tr data-id="{{ $s->id }}">
                    <td>{{ $s->user->name }}</td>
                    <td>{{ $s->level }}</td>
                    <td>{{ $s->start_time }}</td>
                    <td>{{ $s->end_time ?? '-' }}</td>
                    <td>{{ $s->duration ?? '-' }}</td>
                    <td>
                        @if (!$s->end_time)
                            <button class="btn btn-sm btn-danger end-btn">End</button>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @push('scripts')
        <script>
            $(function() {
                $('.end-btn').click(function() {
                    const row = $(this).closest('tr');
                    const id = row.data('id');

                    $.ajax({
                        url: `/admin/idle/${id}/end`,
                        type: 'POST',
                        data: {
                            _token: $('meta[name=csrf-token]').attr('content')
                        },
                        success(res) {
                            alert('Session ended');
                            location.reload();
                        }
                    })
                });
            });
        </script>
    @endpush
@endsection
