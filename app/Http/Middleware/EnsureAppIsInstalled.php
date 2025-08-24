<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EnsureAppIsInstalled
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse|JsonResponse|StreamedResponse) $next
     * @return Response|RedirectResponse|JsonResponse|StreamedResponse
     * @throws BindingResolutionException
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse|JsonResponse|StreamedResponse
    {
        // Skip installer routes to prevent redirect loop
        if ($request->is('install*')) {
            return $next($request);
        }

        if(!app()->make('app.installed'))
            return redirect()->route('installer.index');

        return $next($request);
    }
}
