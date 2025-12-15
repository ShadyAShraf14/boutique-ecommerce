<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * بعد لوجين الأدمن يروح فين؟
     */
    protected $redirectTo = '/admin/dashboard';

    public function __construct()
    {
        // ضيف على admin guard فقط، ما عدا logout
        $this->middleware('guest:admin')->except('logout');
    }

    /**
     * نخلي الـ guard هنا هو admin
     */
    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * صفحة لوجين الأدمن
     */
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    /**
     * بعد ما اللوجين ينجح على admin guard
     * نتأكد إن اليوزر فعلاً عنده رول admin أو supervisor
     */
    protected function authenticated(Request $request, $user)
    {
        // لو ماعندوش رول أدمن أو سوبرفايزر → نطلّعه ونرجّعه لصفحة اللوجين برسالة
        if (! $user->hasAnyRole(['admin', 'supervisor'])) {

            $this->guard()->logout();
            $request->session()->regenerateToken();

            return redirect()
                ->route('admin.login')
                ->withErrors([
                    'email' => 'You are not allowed to access the dashboard.',
                ]);
        }

        // لو تمام → يكمّل عادي للـ redirectTo (/admin/dashboard)
        return redirect()->intended($this->redirectPath());
    }

    /**
     * Logout للأدمن فقط بدون ما يلمس الويب يوزر
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        // ما نعملش invalidate للسيشن عشان ما نبوّظش web user
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
