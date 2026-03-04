<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses {
    protected function ok($data, $message) : JsonResponse {
        return $this->success($data, $message, 200);
    }

    protected function success($data, $message, $statusCode = 200): JsonResponse {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'statusCode' => $statusCode
        ], $statusCode);
    }

    public static function error($payload = [], $statusCode = null): JsonResponse {
        if (is_string($payload)) {
            $payload = [
                [
                    'status' => $statusCode,
                    'message' => $payload
                ]
            ];
        }

        return response()->json([
            'errors' => $payload
        ], $statusCode);
    }
}