<?php

namespace App\Traits;

use App\Models\AppStatus;
use Illuminate\Support\Facades\Cache;

trait InitializeAppStatusTrait
{
    protected $appStatusResult;

    public function initializeAppStatus($authService)
    {
        $this->appStatusResult = Cache::get('app_status_mobile');

        if (!$this->appStatusResult) {
            if (!$this->appStatusResult) {
                $this->appStatusResult = $authService->appDispoRobus(AppStatus::class);
                Cache::put('app_status_mobile', $this->appStatusResult, now()->addMinutes(30));
            }
        }
    }
}
