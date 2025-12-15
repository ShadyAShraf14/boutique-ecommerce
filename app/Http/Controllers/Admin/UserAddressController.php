<?php

// app/Http/Controllers/Admin/UserAddressController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserAddressRequest;
use App\Models\UserAddress;
use App\Models\User;
use App\Models\Country;
use App\Models\State;
use App\Models\City;

class UserAddressController extends Controller
{
    public function index()
    {
        $addresses = UserAddress::with(['user', 'country', 'state', 'city'])
            ->latest()
            ->paginate(20);

        return view('Backend.pages.addresses.index', compact('addresses'));
    }

    public function create()
    {
        $users     = User::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $states    = State::orderBy('name')->get();
        $cities    = City::orderBy('name')->get();

        return view('Backend.pages.addresses.create', compact('users', 'countries', 'states', 'cities'));
    }

    public function store(UserAddressRequest $request)
    {
        $data = $request->validated();

        $data['is_default_shipping'] = $request->boolean('is_default_shipping');
        $data['is_default_billing']  = $request->boolean('is_default_billing');
        $data['is_active']           = $request->boolean('is_active');

        UserAddress::create($data);

        return redirect()->route('admin.addresses.index')
            ->with('success', 'Address created successfully.');
    }

    public function show(UserAddress $address)
    {
        $address->load(['user', 'country', 'state', 'city']);

        return view('Backend.pages.addresses.show', compact('address'));
    }

    public function edit(UserAddress $address)
    {
        $users     = User::orderBy('name')->get();
        $countries = Country::orderBy('name')->get();
        $states    = State::orderBy('name')->get();
        $cities    = City::orderBy('name')->get();

        return view('Backend.pages.addresses.edit', compact('address', 'users', 'countries', 'states', 'cities'));
    }

    public function update(UserAddressRequest $request, UserAddress $address)
    {
        $data = $request->validated();

        $data['is_default_shipping'] = $request->boolean('is_default_shipping');
        $data['is_default_billing']  = $request->boolean('is_default_billing');
        $data['is_active']           = $request->boolean('is_active');

        $address->update($data);

        return redirect()->route('admin.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    public function destroy(UserAddress $address)
    {
        $address->delete();

        return redirect()->route('admin.addresses.index')
            ->with('success', 'Address deleted successfully.');
    }
}
