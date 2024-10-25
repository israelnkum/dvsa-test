<?php

namespace App\Http\Controllers\Trait;

use Exception;
use Illuminate\Support\Facades\Log;

trait Logger
{
    private function logError(string $action, Exception $exception): void
    {
        Log::error("{$action}", [$exception->getMessage()]);
    }
}
