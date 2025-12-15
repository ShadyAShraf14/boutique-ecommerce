<?php

// app/Http/Controllers/Admin/CityController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CityRequest;
use App\Models\City;
use App\Models\State;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('state.country')
            ->orderBy('name')
            ->paginate(20);

        return view('Backend.pages.cities.index', compact('cities'));
    }

    public function create()
    {
        $states = State::with('country')->orderBy('name')->get();

        return view('Backend.pages.cities.create', compact('states'));
    }

    public function store(CityRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        City::create($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City created successfully.');
    }

    public function show(City $city)
    {
        $city->load('state.country');

        return view('Backend.pages.cities.show', compact('city'));
    }

    public function edit(City $city)
    {
        $states = State::with('country')->orderBy('name')->get();

        return view('Backend.pages.cities.edit', compact('city', 'states'));
    }

    public function update(CityRequest $request, City $city)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $city->update($data);

        return redirect()->route('admin.cities.index')
            ->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        $city->delete();

        return redirect()->route('admin.cities.index')
            ->with('success', 'City deleted successfully.');
    }
}
