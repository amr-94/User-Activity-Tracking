<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Idle\StoreIdleRequest;
use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Exception;

class IdleController extends Controller
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->middleware(['role:admin'])->only(['index']);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        try {
            $sessions = $this->repository->index($request);

            if ($request->ajax()) {
                return response()->json(['status' => 'ok', 'data' => $sessions]);
            }

            return view('admin.idle.index', compact('sessions'));
        } catch (Exception $e) {
            report($e);
            return response()->json(['error' => 'Failed to fetch idle sessions', 'message' => $e->getMessage()], 500);
        }
    }
    public function record(StoreIdleRequest $request)
    {
        try {
            $data = $request->validated();
            $session = $this->repository->create($data);

            return response()->json(['status' => 'ok', 'session' => $session]);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'error' => 'Failed to record idle session',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function end($id)
    {
        try {
            $idle = $this->repository->update($id, [
                'end_time' => now()
            ]);

            return response()->json([
                'status' => 'ok',
                'idle' => $idle
            ]);
        } catch (Exception $e) {
            report($e);
            return response()->json([
                'error' => 'Failed to end idle session',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
