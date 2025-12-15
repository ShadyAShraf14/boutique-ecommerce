<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // لو بيخش على أي حاجة تخص الأدمن / الداشبورد / السوبرفايزر
        if ($request->is('admin/*') || $request->is('dashboard') || $request->is('dashboard/*') || $request->is('supervisor/*')) {
            return route('admin.login');
        }

        // باقي الحاجات تروح على لوجين الويب العادي
        return route('login');
    }
}
