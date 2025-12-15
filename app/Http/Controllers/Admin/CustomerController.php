<?php

// app/Http/Controllers/Admin/CustomerController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CustomerRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::customers()->latest()->paginate(15);

        return view('Backend.pages.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('Backend.pages.customers.create');
    }

    public function store(CustomerRequest $request)
    {
        $data = $request->validated();
        $data['password']  = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active');

        $customer = User::create($data);
        $customer->assignRole('customer');

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(User $customer)
    {
        return view('Backend.pages.customers.show', compact('customer'));
    }

    public function edit(User $customer)
    {
        return view('Backend.pages.customers.edit', compact('customer'));
    }

    public function update(CustomerRequest $request, User $customer)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $customer->update($data);

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(User $customer)
    {
        $customer->delete();

        return redirect()
            ->route('admin.customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
