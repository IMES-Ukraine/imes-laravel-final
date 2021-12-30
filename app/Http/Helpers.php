<?php

namespace App\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class Helpers
{

    public function apiArrayResponseBuilder($statusCode = null, $message = null, $data = []): JsonResponse
    {
        $arr = [
            'status_code' => (isset($statusCode)) ? $statusCode : 500,
            'message' => (isset($message)) ? $message : 'error'
        ];

        $arr['data'] = (array)$data;
Log::info('send to App', $arr);
        return response()->json($arr, $arr['status_code']);
    }

    public function generateUserName($phone): string
    {
        return $phone .  '@imes.pro';
    }
}
