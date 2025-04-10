<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && decrypt(Auth::user()->role) === 'admin') {
            return $next($request); // Cho phép truy cập
        }

        return redirect('/')->with('error', 'Bạn không có quyền truy cập.');
    }
}
