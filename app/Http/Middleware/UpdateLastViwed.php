<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use App\Events\DateUpdate;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UpdateLastViwed
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
        return $next($request);
        Auth::user()->last_viwed_user = Carbon::now();
        Auth::user()->save();
        #event(new DateUpdate(Auth::user()));    
    }
}
