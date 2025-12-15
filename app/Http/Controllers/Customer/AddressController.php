<?php

namespace App\Http\Controllers\Customer;
use Illuminate\Database\QueryException;

use App\Http\Controllers\Controller;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;



class AddressController extends Controller
{
    /**
     * قائمة عناوين العميل
     */
    public function index()
    {
        $addresses = UserAddress::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('Customer.addresses.index', compact('addresses'));
    }

    /**
     * فورم إضافة عنوان جديد
     */
    public function create()
    {
        $address = new UserAddress();

        return view('Customer.addresses.create', compact('address'));
    }

    /**
     * حفظ عنوان جديد
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // المدخلات اللي جاية من الفورم
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone'   => ['required', 'string', 'max:50'],
        ]);

        $data['user_id'] = $user->id;

        // تقسيم الاسم -> first_name / last_name
        $fullName = trim($data['name']);
        $parts    = preg_split('/\s+/', $fullName);

        $data['first_name'] = $parts[0] ?? $fullName;
        $data['last_name']  = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : null;

        // نضمن إن address_line1 يتعبّى من نفس حقل الـ textarea
        $data['address_line1'] = $data['address'];
        $data['address_line2'] = null;    // اختياري
        $data['postal_code']   = null;    // لو العمود موجود ومسموح يكون NULL

        // نجيب آخر عنوان للـ user عشان ناخد منه country/state/city
        $lastAddress = UserAddress::where('user_id', $user->id)
            ->latest()
            ->first();

        if ($lastAddress) {
            $data['country_id'] = $lastAddress->country_id;
            $data['state_id']   = $lastAddress->state_id;
            $data['city_id']    = $lastAddress->city_id;
        } else {
            // عدّل ID البلد الافتراضية حسب الداتا عندك
            $data['country_id'] = 1;
            $data['state_id']   = null;
            $data['city_id']    = null;
        }

        UserAddress::create($data);

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'Address added successfully.');
    }

    /**
     * فورم تعديل عنوان
     */
    public function edit(UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        return view('Customer.addresses.edit', compact('address'));
    }

    /**
     * تحديث عنوان قائم
     */
    public function update(Request $request, UserAddress $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:500'],
            'phone'   => ['required', 'string', 'max:50'],
        ]);

        // إعادة تقسيم الاسم
        $fullName = trim($data['name']);
        $parts    = preg_split('/\s+/', $fullName);

        $data['first_name'] = $parts[0] ?? $fullName;
        $data['last_name']  = isset($parts[1]) ? implode(' ', array_slice($parts, 1)) : null;

        // تحديث address_line1 من textarea
        $data['address_line1'] = $data['address'];

        $address->update($data);

        return redirect()
            ->route('customer.addresses.index')
            ->with('success', 'Address updated successfully.');
    }

    /**
     * حذف عنوان
     */
public function destroy(UserAddress $address)
{
    if ($address->user_id !== Auth::id()) {
        abort(403);
    }

    try {
        $address->delete();
    } catch (QueryException $e) {
        if ($e->getCode() === '23000') {
            return redirect()
                ->route('customer.addresses.index')
                ->with('error', 'This address is linked to previous orders and cannot be deleted.');
        }

        throw $e;
    }

    return redirect()
        ->route('customer.addresses.index')
        ->with('success', 'Address deleted.');
}
}
