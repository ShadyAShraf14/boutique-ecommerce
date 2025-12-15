<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingMethod;
use App\Models\Country;
use Illuminate\Http\Request;

class ShippingMethodController extends Controller
{
    public function index()
    {
        $methods = ShippingMethod::with('country')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('Backend.pages.shippingway.index', compact('methods'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        return view('Backend.pages.shippingway.create', compact('countries'));
    }

public function store(Request $request)
{
    // نحول قيمة checkbox إلى true/false
    $request->merge([
        'is_active' => $request->has('is_active'),
    ]);

    $data = $request->validate([
        'name'       => 'required|string|max:255',
        'code'       => 'nullable|string|max:255',
        'price'      => 'required|numeric|min:0',
        'country_id' => 'nullable|exists:countries,id',
        'is_active'  => 'boolean',
    ]);

    ShippingMethod::create($data);

    return redirect()
        ->route('admin.shippingway.index')
        ->with('success', 'Shipping method created successfully.');
}
    public function edit(ShippingMethod $shippingway)
    {
        $countries = Country::orderBy('name')->get();
        return view('Backend.pages.shippingway.edit', compact('shippingway', 'countries'));
    }

public function update(Request $request, ShippingMethod $shippingway)
{
    $request->merge([
        'is_active' => $request->has('is_active'),
    ]);

    $data = $request->validate([
        'name'       => 'required|string|max:255',
        'code'       => 'nullable|string|max:255',
        'price'      => 'required|numeric|min:0',
        'country_id' => 'nullable|exists:countries,id',
        'is_active'  => 'boolean',
    ]);

    $shippingway->update($data);

    return redirect()
        ->route('admin.shippingway.index')
        ->with('success', 'Shipping method updated successfully.');
}

    public function destroy(ShippingMethod $shippingway)
    {
        $shippingway->delete();

        return redirect()
            ->route('admin.shippingway.index')
            ->with('success', 'Shipping method deleted.');
    }
}
