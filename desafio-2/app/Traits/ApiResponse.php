<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

/**
 * Trait para padronização das respostas da API.
 */
trait ApiResponse
{
    /**
     * Resposta de Sucesso
     */
    protected function success($data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json(array_filter([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ]), $code);
    }

    /**
     * Resposta de Erro
     */
    protected function error(string $message, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }
}
