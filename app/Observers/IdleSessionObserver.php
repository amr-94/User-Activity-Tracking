<?php

namespace App\Observers;



use App\Models\IdleSession;
use App\Repositories\ActivityRepository;

class IdleSessionObserver
{
    protected $activityRepository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function created(IdleSession $idleSession)
    {
        $this->activityRepository->create([
            'user_id' => $idleSession->user_id,
            'action' => 'idle_session_started',
            'model' => 'IdleSession',
            'record_id' => $idleSession->id,
        ]);
    }

    public function updated(IdleSession $idleSession)
    {
        if ($idleSession->isDirty('status')) {
            $action = $idleSession->status === 'active' ? 'idle_session_resumed' : 'idle_session_paused';

            $this->activityRepository->create([
                'user_id' => $idleSession->user_id,
                'action' => $action,
                'model' => 'IdleSession',
                'record_id' => $idleSession->id,
            ]);
        }

        if ($idleSession->isDirty('end_time')) {
            $this->activityRepository->create([
                'user_id' => $idleSession->user_id,
                'action' => 'idle_session_ended',
                'model' => 'IdleSession',
                'record_id' => $idleSession->id,
            ]);
        }
    }

    public function deleted(IdleSession $idleSession)
    {
        $this->activityRepository->create([
            'user_id' => $idleSession->user_id,
            'action' => 'idle_session_deleted',
            'model' => 'IdleSession',
            'record_id' => $idleSession->id,
        ]);
    }
}
