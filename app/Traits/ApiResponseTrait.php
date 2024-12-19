<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ApiResponseTrait
{
    protected function successResponse(
        string $message,
        mixed $data = null,
        int $status = Response::HTTP_OK,
        bool $hasPagination = false
    ): JsonResponse {
        return response()->json(
            array_merge(
                [
                    'success' => true,
                    'status' => $status,
                    'message' => $message,
                ],
                $hasPagination ? $data : ['data' => $data]
            ),
            $status
        );
    }

    protected function errorResponse(
        string $message,
        int $status = Response::HTTP_BAD_REQUEST,
        mixed $errors = null
    ): JsonResponse {
        return response()->json([
            'success' => false,
            'status' => $status,
            'message' => $message,
            'errors' => $errors,
        ], $status);
    }

    protected function noContentResponse(): JsonResponse
    {
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}