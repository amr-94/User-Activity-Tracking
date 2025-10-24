<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ActivityController extends Controller
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $activities = $this->repository->index($request);

        if ($request->ajax()) {
            return response()->json(['status' => 'ok', 'data' => $activities]);
        }

        return view('admin.activities.index', compact('activities'));
    }


    public function destroy($id)
    {
        $this->repository->delete($id);
        return back()->with('success', 'Activity removed');
    }
}
