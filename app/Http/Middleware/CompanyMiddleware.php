<?php

namespace App\Http\Middleware;

use App\Models\AppCompanyStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $appStatus = AppCompanyStatus::latest()->first();
        
        if ($appStatus !== null && $appStatus->status_app === 'closed') {
            return response()->view('errors.599', [
                'message' => $appStatus->description,
                'dateOpen' => $appStatus->time_start,
                'dateClose' => $appStatus->time_end,
            ], 599);
        }
        if (Auth::check() && !sabdyCompanyVerify()) {
            return response()->view('errors.599', [], 599);
        }

        return $next($request);
    }
}
