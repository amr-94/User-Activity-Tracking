<?php

namespace App\Repositories;

use App\Http\Requests\Penalty\StorePenaltyRequest;
use App\Interfaces\BaseRepositoryInterface;
use App\Models\IdleSession;
use App\Models\Penalty;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class IdleRepository implements BaseRepositoryInterface
{
    public function index($request)
    {
        try {
            $query = IdleSession::with('user')->latest();

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            return $query->paginate(10)->withQueryString();
        } catch (Exception $e) {
            Log::error('Error in IdleRepository@index: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find($id)
    {
        try {
            return IdleSession::with('user')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Idle session not found with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in IdleRepository@find: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            $session = IdleSession::create($data);

            if ($data['level'] === 3) {
                try {
                    $penaltyData = [
                        'user_id' => $data['user_id'],
                        'reason' => 'Exceeded maximum idle time',
                        'count' => 1,
                        'date' => now(),
                    ];

                    $request = new StorePenaltyRequest();
                    $validator = Validator::make($penaltyData, $request->rules());

                    if ($validator->fails()) {
                        throw new ValidationException($validator);
                    }

                    Penalty::create($penaltyData);
                } catch (ValidationException $e) {
                    Log::error('Validation error creating penalty: ' . $e->getMessage());
                    throw $e;
                } catch (Exception $e) {
                    Log::error('Error creating penalty: ' . $e->getMessage());
                    throw $e;
                }
            }

            return $session;
        } catch (Exception $e) {
            Log::error('Error in IdleRepository@create: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $idle = $this->find($id);
            $idle->update($data);
            return $idle;
        } catch (ModelNotFoundException $e) {
            Log::error('Idle session not found for update with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in IdleRepository@update: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            return $this->find($id)->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Idle session not found for deletion with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in IdleRepository@delete: ' . $e->getMessage());
            throw $e;
        }
    }
}
