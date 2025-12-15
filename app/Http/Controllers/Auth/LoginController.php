<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * بعد لوجين الويب يوزر (العملاء) يروح فين؟
     */
    protected $redirectTo = '/';

    public function __construct()
    {
        // ضيوف على web guard فقط
        $this->middleware('guest:web')->except('logout');
    }

    /**
     * الجارد الخاص بالويب سايت (customers)
     */
    protected function guard()
    {
        return Auth::guard('web');
    }

    /**
     * Logout للمستخدم العادي فقط
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        // برضه ما نعملش invalidate عشان الأدمن ما يطلعش
        $request->session()->regenerateToken();

        return redirect()->route('frontend.home');
    }

    // مش محتاجين أي توزيع حسب رول هنا،
    // خلي لوجين الويب يرجّع على الهوم وخلاص
    // protected function authenticated(Request $request, $user)
    // {
    //     return redirect()->route('frontend.home');
    // }
}
