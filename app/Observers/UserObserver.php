<?php

namespace App\Observers;

use App\Models\User;
use App\Repositories\ActivityRepository;

class UserObserver
{
    protected $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function created(User $user)
    {
        $this->activityRepository->create([
            'user_id' => auth()->id() ?? $user->id,
            'action' => 'user_created',
            'model' => 'User',
            'record_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function updated(User $user)
    {
        $this->activityRepository->create([
            'user_id' => auth()->id() ?? $user->id,
            'action' => 'user_updated',
            'model' => 'User',
            'record_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }

    public function deleted(User $user)
    {
        $this->activityRepository->create([
            'user_id' => auth()->id() ?? $user->id,
            'action' => 'user_deleted',
            'model' => 'User',
            'record_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
}
