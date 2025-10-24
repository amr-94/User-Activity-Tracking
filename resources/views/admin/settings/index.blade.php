@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">System Settings</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Idle Timeout (minutes)</label>
                    <input type="number" name="idle_timeout" class="form-control"
                        value="{{ $settings['idle_timeout'] ?? 5 }}" min="1">
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="monitoring_enabled" value="1"
                            {{ ($settings['monitoring_enabled'] ?? 1) == 1 ? 'checked' : '' }}>
                        <label class="form-check-label">Enable Activity Monitoring</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
        </div>
    </div>
@endsection
