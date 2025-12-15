<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DashboardController extends Controller
{
    /**
     * صفحة الـ Dashboard (الصفحة الرئيسية لحساب العميل)
     */
    public function index()
    {
        $user = Auth::user();

        // إحصائيات بسيطة – عداد أوردرات + آخر أوردر
        $ordersQuery = Order::where('user_id', $user->id)->latest();

        $ordersCount   = $ordersQuery->count();
        $lastOrder     = $ordersQuery->first();
        $lastOrderCode = $lastOrder ? ($lastOrder->ref_code ?? $lastOrder->id) : null;

        return view('Customer.dashboard', [
            'ordersCount'   => $ordersCount,
            'lastOrderCode' => $lastOrderCode,
        ]);
    }

    /**
     * صفحة عرض بيانات البروفايل
     */
    public function profile()
    {
        $user = Auth::user();

        return view('Customer.profile', [
            'user' => $user,
        ]);
    }

    /**
     * تحديث بيانات البروفايل
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        // Validation
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // تحديث الاسم والإيميل
        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        // لو فيه باسورد جديد
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }

        $user->save();

        return redirect()
            ->route('customer.profile')
            ->with('success', 'Profile updated successfully.');
    }
}
