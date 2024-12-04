<?php

namespace App\Http\Middleware;

use Closure;
use App\Providers\RouteServiceProvider;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = auth()->user();
        if($user):
            if($user->is_admin == null):
                return redirect(RouteServiceProvider::HOME);
            endif;
        else:
            return redirect()->route('login');
        endif;

        return $next($request);
    }
}
