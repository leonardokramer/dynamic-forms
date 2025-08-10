<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect(route('filament.admin.auth.login'));
        }

        if (!auth()->user()->isAdmin) {
            return redirect(route('filament.public.pages.' . __('forms.pages.list_active_forms.slug')));
        }

        return $next($request);
    }
}
