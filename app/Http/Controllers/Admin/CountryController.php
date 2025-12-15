<?php

// app/Http/Controllers/Admin/CountryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Models\Country;

class CountryController extends Controller
{
public function index()
{
    $countries = Country::withCount(['states', 'cities', 'addresses'])
        ->orderBy('name')
        ->paginate(20);

    return view('Backend.pages.countries.index', compact('countries'));
}

    public function create()
    {
        return view('Backend.pages.countries.create');
    }

    public function store(CountryRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        Country::create($data);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country created successfully.');
    }

    public function show(Country $country)
    {
        return view('Backend.pages.countries.show', compact('country'));
    }

    public function edit(Country $country)
    {
        return view('Backend.pages.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $country->update($data);

        return redirect()->route('admin.countries.index')
            ->with('success', 'Country updated successfully.');
    }

public function destroy(Country $country)
{
    // لو ليها states / محافظات ممنوع الحذف
    if ($country->states()->exists()) {
        return back()->withErrors(
            'لا يمكن حذف هذه الدولة لأنها مرتبطة بمحافظات / مدن / عناوين عملاء.'
        );
    }

    // لو مفيش أي states تابعة لها: احذفي عادي
    $country->delete();

    return redirect()
        ->route('admin.countries.index')
        ->with('success', 'Country deleted successfully.');
}
}
