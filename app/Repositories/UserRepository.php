<?php

namespace App\Repositories;

use App\Models\User;
use App\Interfaces\BaseRepositoryInterface;
use Illuminate\Http\Request;

class UserRepository implements BaseRepositoryInterface
{
    public function index($request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        }

        return $query->paginate(10)->withQueryString();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }

    public function create(array $data)
    {
        return User::create($data);
    }

    public function update($id, array $data)
    {
        $user = $this->find($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
}
