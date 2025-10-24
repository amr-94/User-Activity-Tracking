<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\ActivityLog;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ActivityRepository implements BaseRepositoryInterface
{
    public function index($request)
    {
        try {
            $query = ActivityLog::with('user')->latest();

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('action', 'like', "%{$search}%");
            }

            return $query->paginate(10)->withQueryString();
        } catch (Exception $e) {
            Log::error('Error in ActivityRepository@index: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find($id)
    {
        try {
            return ActivityLog::with('user')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Activity not found with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in ActivityRepository@find: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            return ActivityLog::create([
                'user_id' => $data['user_id'],
                'action' => $data['action'],
                'model' => $data['model'],
                'record_id' => $data['record_id'] ?? null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (Exception $e) {
            Log::error('Error in ActivityRepository@create: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $activity = $this->find($id);
            $activity->update($data);
            return $activity;
        } catch (ModelNotFoundException $e) {
            Log::error('Activity not found for update with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in ActivityRepository@update: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            return $this->find($id)->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Activity not found for deletion with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in ActivityRepository@delete: ' . $e->getMessage());
            throw $e;
        }
    }
}
