<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Unified API response format for all controllers.
 * Every API response MUST use this trait — no exceptions.
 * Format: { success, data, message, errors }
 */
trait ApiResponsable
{
    protected function success(mixed $data = null, string $message = '', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => $data,
            'message' => $message,
            'errors'  => null,
        ], $status);
    }

    protected function created(mixed $data = null, string $message = 'تم الإنشاء بنجاح.'): JsonResponse
    {
        return $this->success($data, $message, 201);
    }

    protected function error(string $message, mixed $errors = null, int $status = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data'    => null,
            'message' => $message,
            'errors'  => $errors,
        ], $status);
    }

    protected function notFound(string $message = 'العنصر غير موجود.'): JsonResponse
    {
        return $this->error($message, null, 404);
    }

    protected function unauthorized(string $message = 'غير مصرح.'): JsonResponse
    {
        return $this->error($message, null, 401);
    }

    protected function forbidden(string $message = 'ليس لديك صلاحية.'): JsonResponse
    {
        return $this->error($message, null, 403);
    }
}
