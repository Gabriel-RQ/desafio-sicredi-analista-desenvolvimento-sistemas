<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                $isDebug = config('app.debug');

                // Estrutura base da resposta
                $response = [
                    'message' => $isDebug ? $e->getMessage() : 'Ocorreu um erro interno no servidor.',
                ];

                // Só adiciona detalhes técnicos se o debug estiver ligado
                if ($isDebug) {
                    $response['debug'] = [
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => collect($e->getTrace())->take(5), // Apenas os 5 primeiros passos
                    ];
                }

                // Define o status code (padrão 500 para erros desconhecidos)
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                // Exceção para erros de validação (que devem ser 422)
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    $statusCode = 422;
                    $response['message'] = 'Dados inválidos.';
                    $response['errors'] = $e->errors();
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $statusCode = 404;
                    $response['message'] = 'Registro não encontrado';
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
                    $statusCode = 401;
                    $response['message'] = 'Não autorizado. O token é inválido.';
                }

                Log::error(get_class($e).' '.$e->getMessage());

                return response()->json($response, $statusCode);
            }
        });
    })->create();
