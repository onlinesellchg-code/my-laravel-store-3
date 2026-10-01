<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->get('admin_authenticated', false)) {
            return redirect()->route('admin.login')->with('message', 'لطفاً ابتدا وارد پنل مدیریت شوید.');
        }

        return $next($request);
    }
}
