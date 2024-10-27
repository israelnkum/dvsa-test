<?php

namespace App\Http\Controllers\Trait;

use Exception;
use Illuminate\Support\Facades\Log;
use JsonException;

trait Helpers
{
    private function logError(string $action, Exception $exception): void
    {
        Log::error("{$action}", [$exception->getMessage()]);
    }

    /**
     * @throws JsonException
     */
    private function getCacheKey(string $prefix, $data): string
    {
        return $prefix. "_" . md5(json_encode($data, JSON_THROW_ON_ERROR));
    }
}
