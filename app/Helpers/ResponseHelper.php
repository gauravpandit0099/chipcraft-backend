<?php

namespace App\Helpers;
use Illuminate\Http\JsonResponse;
class ResponseHelper {

    /* return standardized success JSON */
    public static function success($data = null, string $message = 'Success', int $code = 200): JsonResponse {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /* return standardized error JSON */
    public static function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}