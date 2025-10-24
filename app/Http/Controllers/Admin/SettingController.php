<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class SettingController extends Controller
{
    protected $repository;

    public function __construct(BaseRepositoryInterface $repository)
    {
        $this->middleware(['auth']);
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $settings = $this->repository->index($request);
        return view('admin.settings.index', ['settings' => $settings]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'idle_timeout' => 'required|integer|min:1',
            'monitoring_enabled' => 'required|in:0,1',
        ]);

        foreach ($data as $key => $value) {
            $this->repository->update($key, ['value' => (string) $value]);
        }

        if ($request->ajax()) {
            return response()->json(['status' => 'ok']);
        }

        return back()->with('success', 'Settings saved');
    }

    public function getSettings()
    {
        $settings = $this->repository->index(request());
        return response()->json($settings);
    }
}
