<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingCompany;
use Illuminate\Http\Request;
use App\Http\Requests\StoreShippingCompanyRequest;
use App\Http\Requests\UpdateShippingCompanyRequest;

class ShippingCompanyController extends Controller
{
    public function index()
    {
        $companies = ShippingCompany::orderBy('name')->paginate(20);
        return view('Backend.pages.shipping_companies.index', compact('companies'));
    }

    public function create()
    {
        return view('Backend.pages.shipping_companies.create');
    }

    public function store(StoreShippingCompanyRequest $request)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        ShippingCompany::create($data);

        return redirect()->route('admin.shipping_companies.index')
            ->with('success', 'Shipping company created successfully.');
    }

    public function show(ShippingCompany $shipping_company)
    {
        return view('Backend.pages.shipping_companies.show', compact('shipping_company'));
    }

    public function edit(ShippingCompany $shipping_company)
    {
        return view('Backend.pages.shipping_companies.edit', compact('shipping_company'));
    }

    public function update(UpdateShippingCompanyRequest $request, ShippingCompany $shipping_company)
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');

        $shipping_company->update($data);

        return redirect()->route('admin.shipping_companies.index')
            ->with('success', 'Shipping company updated successfully.');
    }

    public function destroy(ShippingCompany $shipping_company)
    {
        $shipping_company->delete();

        return redirect()->route('admin.shipping_companies.index')
            ->with('success', 'Shipping company deleted successfully.');
    }
}
