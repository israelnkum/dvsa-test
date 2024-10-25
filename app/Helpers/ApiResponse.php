<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ApiResponse extends JsonResponse
{
    public function __construct($data = null, int $status = 200, array $headers = [], int $options = 0)
    {
        parent::__construct($data, $status, $headers, $options);
    }

    /**
     * @param $message
     * @param int $status
     * @param array $additionalData
     * @return static
     */
    public static function error($message, int $status = 400, array $additionalData = []): static
    {
        $responseData = [
            'success' => false,
            'error' => [
                'message' => $message,
            ],
        ];

        $responseData = array_merge($responseData, $additionalData);

        return new static($responseData, $status);
    }

    public static function success($data = [], $status = 200): static
    {
        $responseData = [
            'success' => true,
            'data' => $data
        ];

        return new static($responseData, $status);
    }
}
