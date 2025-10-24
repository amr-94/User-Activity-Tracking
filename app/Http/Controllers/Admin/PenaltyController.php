<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PenaltyController extends Controller
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->middleware(['auth']);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $penalties = $this->repository->index($request);

        if ($request->ajax()) {
            return response()->json(['status' => 'ok', 'data' => $penalties]);
        }

        return view('admin.penalties.index', compact('penalties'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
        ]);

        $penalty = $this->repository->create([
            'user_id' => $request->post('user_id'),
            'reason' => $request->post('reason'),
            'count' => 1,
            'date' => now()->toDateString(),
        ]);

        if ($request->ajax()) {
            return response()->json(['status' => 'ok', 'penalty' => $penalty]);
        }

        return back()->with('success', 'Penalty applied');
    }

    public function destroy($id, Request $request)
    {
        $this->repository->delete($id);

        if ($request->ajax()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('success', 'Penalty removed');
    }
}
