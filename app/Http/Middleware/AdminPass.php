<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminPass
{
    public function handle(Request $request, Closure $next)
    // Verifier si utilisateur connecté & si utilisateur admin, sinon redirect vers login
    {
        if(!$request->user() || !$request->user()->isAdmin){
            return redirect()->route('login');
        }
        return $next($request);
    }
}