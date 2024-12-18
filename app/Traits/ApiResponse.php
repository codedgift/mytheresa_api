<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Return a standardized success response.
     *
     * @param mixed $data
     * @param string $message
     * @param array|null $meta
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse(
        mixed $data,
        string $message = 'Request successful',
        ?array $meta = null,
        int $statusCode = Response::HTTP_OK
    ): JsonResponse {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Return a standardized error response.
     *
     * @param string $message
     * @param array|null $errors
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function errorResponse(
        string $message = 'An unexpected error occurred',
        ?array $errors = null,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR
    ): JsonResponse {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }
}
