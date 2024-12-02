<?php

namespace App\Http\Middleware;

use App\Http\Services\AuthenticationService;
use App\Models\AppCompanyStatus;
use App\Models\AppStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CompanyMiddleware
{
     /**
     * @var AuthService
     */
    protected $authService;

    /**
     * Constructor to inject dependencies.
     *
     * @param AuthService $authService
     */
    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
   
      
        $appStatusResult = $this->authService->appDispoCompanieRobus(AppCompanyStatus::class);

        if (!$appStatusResult['success']) {
            return response()->view('errors.599', [
                'message' => $appStatusResult['message'],
            ], 599);
        }
        if (Auth::check() && !sabdyCompanyVerify()) {
            return response()->view('errors.599', [], 599);
        }

        return $next($request);
    }
}
