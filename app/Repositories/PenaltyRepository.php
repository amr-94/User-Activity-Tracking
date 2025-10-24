<?php

namespace App\Repositories;

use App\Models\Penalty;
use App\Interfaces\BaseRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PenaltyRepository implements BaseRepositoryInterface
{
    public function index($request)
    {
        try {
            $query = Penalty::with('user')->latest();

            if ($request->has('search')) {
                $search = $request->get('search');
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('reason', 'like', "%{$search}%");
            }

            return $query->paginate(10)->withQueryString();
        } catch (Exception $e) {
            Log::error('Error in PenaltyRepository@index: ' . $e->getMessage());
            throw $e;
        }
    }

    public function find($id)
    {
        try {
            return Penalty::with('user')->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Log::error('Penalty not found with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in PenaltyRepository@find: ' . $e->getMessage());
            throw $e;
        }
    }

    public function create(array $data)
    {
        try {
            return Penalty::create($data);
        } catch (Exception $e) {
            Log::error('Error in PenaltyRepository@create: ' . $e->getMessage());
            throw $e;
        }
    }

    public function update($id, array $data)
    {
        try {
            $penalty = $this->find($id);
            $penalty->update($data);
            return $penalty;
        } catch (ModelNotFoundException $e) {
            Log::error('Penalty not found for update with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in PenaltyRepository@update: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete($id)
    {
        try {
            return $this->find($id)->delete();
        } catch (ModelNotFoundException $e) {
            Log::error('Penalty not found for deletion with ID: ' . $id);
            throw $e;
        } catch (Exception $e) {
            Log::error('Error in PenaltyRepository@delete: ' . $e->getMessage());
            throw $e;
        }
    }
}
