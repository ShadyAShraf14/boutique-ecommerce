<?php

// app/Http/Controllers/Admin/StateController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StateRequest;
use App\Models\State;
use App\Models\Country;

class StateController extends Controller
{
    public function index()
    {
        $states = State::with('country')
            ->orderBy('name')
            ->paginate(20);

        return view('Backend.pages.states.index', compact('states'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();

        return view('Backend.pages.states.create', compact('countries'));
    }

    public function store(StateRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        State::create($data);

        return redirect()->route('admin.states.index')
            ->with('success', 'State created successfully.');
    }

    public function show(State $state)
    {
        $state->load('country');

        return view('Backend.pages.states.show', compact('state'));
    }

    public function edit(State $state)
    {
        $countries = Country::orderBy('name')->get();

        return view('Backend.pages.states.edit', compact('state', 'countries'));
    }

    public function update(StateRequest $request, State $state)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $state->update($data);

        return redirect()->route('admin.states.index')
            ->with('success', 'State updated successfully.');
    }

    public function destroy(State $state)
    {
        $state->delete();

        return redirect()->route('admin.states.index')
            ->with('success', 'State deleted successfully.');
    }
}
