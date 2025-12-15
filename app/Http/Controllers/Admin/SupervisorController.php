<?php

// app/Http/Controllers/Admin/SupervisorController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest; // نفس الفاليديشن
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SupervisorController extends Controller
{
    public function index()
    {
        $supervisors = User::supervisors()->latest()->paginate(15);

        return view('Backend.pages.supervisors.index', compact('supervisors'));
    }

    public function create()
    {
        return view('Backend.pages.supervisors.create');
    }

    public function store(CustomerRequest $request)
    {
        $data = $request->validated();
        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active');

        $supervisor = User::create($data);
        $supervisor->assignRole('supervisor'); // المهم ده

        return redirect()
            ->route('admin.supervisors.index')
            ->with('success', 'Supervisor created successfully.');
    }

    public function show(User $supervisor)
    {
        return view('Backend.pages.supervisors.show', compact('supervisor'));
    }

    public function edit(User $supervisor)
    {
        return view('Backend.pages.supervisors.edit', compact('supervisor'));
    }

    public function update(CustomerRequest $request, User $supervisor)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $supervisor->update($data);

        return redirect()
            ->route('admin.supervisors.index')
            ->with('success', 'Supervisor updated successfully.');
    }
}
