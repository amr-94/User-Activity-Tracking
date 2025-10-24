<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\users\StoreUserRequst;
use App\Http\Requests\users\UpdateUserRequst;
use App\Interfaces\BaseRepositoryInterface;
use App\Models\ActivityLog;
use App\Models\IdleSession;
use App\Models\Penalty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $users = $this->repository->index($request);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequst $request)
    {
        $validated = $request->validated();
        Log::info($request);

        if (isset($validated['avatar'])) {
            $path = $validated['avatar']->store('avatars', 'public');
            $validated['avatar_path'] = $path;
        }

        $validated['password'] = bcrypt($validated['password']);

        $user = $this->repository->create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User created');
    }

    public function edit($id)
    {
        $user = $this->repository->find($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequst $request, $id)
    {
        $validated = $request->validated();
        Log::info($validated);

        if (isset($validated['avatar'])) {
            $user = $this->repository->find($id);
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $path = $validated['avatar']->store('avatars', 'public');
            $validated['avatar_path'] = $path;
        }

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user = $this->repository->update($id, $validated);


        return redirect()->route('admin.users.index')->with('success', 'User updated');
    }

    public function destroy($id)
    {
        $user = $this->repository->find($id);
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        $this->repository->delete($id);

        if (request()->ajax()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('success', 'User deleted');
    }

    public function downloadAvatar($id)
    {
        $user = $this->repository->find($id);
        if (!$user->avatar_path) {
            abort(404);
        }
        return response()->download(storage_path('app/public/' . $user->avatar_path));
    }

    public function show($id)
    {
        $user = User::with([
            'activityLogs',
            'idleSessions',
            'penalties'
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function deleteActivities($id)
    {
        ActivityLog::where('user_id', $id)->delete();
        return response()->json(['message' => 'All activities deleted successfully']);
    }

    public function deletePenalties($id)
    {
        Penalty::where('user_id', $id)->delete();
        return response()->json(['message' => 'All penalties deleted successfully']);
    }

    public function deleteIdleSessions($id)
    {
        IdleSession::where('user_id', $id)->delete();
        return response()->json(['message' => 'All idle sessions deleted successfully']);
    }
}
