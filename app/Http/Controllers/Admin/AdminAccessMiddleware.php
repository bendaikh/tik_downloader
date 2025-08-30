<?php

namespace App\Http\Controllers\Admin;

use Closure;
use Illuminate\Http\Request;

trait AdminAccessMiddleware
{
    protected function makeIsAdminMiddleware()
    {
        return function (Request $request, Closure $next) {
            if (!auth()->user()->is_admin && !auth()->user()->is_demo) {
                return back()->with('message', [
                    'type' => 'error',
                    'content' => 'Only admins can access this page or action.'
                ]);
            }

            return $next($request);
        };
    }

    protected function makeDemoRestrictionMiddleware()
    {
        return function (Request $request, Closure $next) {
            if (auth()->user()->is_demo && $request->isMethod('POST')) {
                return back()->with('message', [
                    'type' => 'warning',
                    'content' => 'This section is not available in demo mode. Critical settings like AI integration, payment settings, analytics, and general settings are restricted to prevent unauthorized modifications.'
                ]);
            }

            return $next($request);
        };
    }
}
