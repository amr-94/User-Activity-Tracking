<?php

namespace App\Observers;



use App\Models\Penalty;
use App\Repositories\ActivityRepository;

class PenaltyObserver
{
    protected $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function created(Penalty $penalty)
    {
        $this->activityRepository->create([
            'user_id' => $penalty->user_id,
            'action' => 'penalty_created',
            'model' => 'Penalty',
            'record_id' => $penalty->id,
        ]);
    }

    public function updated(Penalty $penalty)
    {
        if ($penalty->isDirty('status')) {
            $action = match ($penalty->status) {
                'approved' => 'penalty_approved',
                'rejected' => 'penalty_rejected',
                default => 'penalty_updated'
            };

            $this->activityRepository->create([
                'user_id' => $penalty->user_id,
                'action' => $action,
                'model' => 'Penalty',
                'record_id' => $penalty->id,
            ]);
        }
    }

    public function deleted(Penalty $penalty)
    {
        $this->activityRepository->create([
            'user_id' => $penalty->user_id,
            'action' => 'penalty_deleted',
            'model' => 'Penalty',
            'record_id' => $penalty->id,
        ]);
    }
}
