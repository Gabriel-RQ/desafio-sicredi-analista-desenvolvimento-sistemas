<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {})
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $e, Request $request) {

            // Só interfere se for uma requisição de API ou esperar JSON
            if ($request->is('api/*') || $request->wantsJson()) {

                $statusCode = 500;
                $response = [
                    'success' => false,
                    'message' => 'Erro interno do servidor.',
                ];

                // Tratamento de Exceções Específicas do Framework/Bibliotecas

                // Erros de Validação (422)
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    $statusCode = 422;
                    $response['message'] = 'Dados inválidos.';
                    $response['errors'] = $e->errors(); // Retorna o array de campos inválidos
                }
                // Erros de Autenticação (401)
                elseif ($e instanceof \Illuminate\Auth\AuthenticationException || $e instanceof \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException) {
                    $statusCode = 401;
                    $response['message'] = 'Não autenticado ou não autorizado.';
                }
                // Registro não encontrado (Model::findOrFail) (404)
                elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException || $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $statusCode = 404;
                    $response['message'] = 'Registro ou recurso não encontrado.';
                }
                // Exceções HTTP genéricas (abort(403), abort(400), etc)
                elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface) {
                    $statusCode = $e->getStatusCode();
                    $response['message'] = $e->getMessage();
                }

                // Tratamento de Exceções Customizadas de Domínio
                elseif (method_exists($e, 'getStatusCode')) {
                    $statusCode = $e->getStatusCode();
                    $response['message'] = $e->getMessage();
                }
                // Se não tiver status code, mas não for erro crítico (Exception lógica)
                elseif ($e instanceof \DomainException || $e instanceof \InvalidArgumentException) {
                    $statusCode = 400;
                    $response['message'] = $e->getMessage();
                }

                // Caso for debug
                if (config('app.debug') && $statusCode >= 500) {
                    $response['debug'] = [
                        'message' => $e->getMessage(),
                        'exception' => get_class($e),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'trace' => collect($e->getTrace())->take(5),
                    ];
                }

                if ($statusCode >= 500) {
                    Log::error($e->getMessage(), ['exception' => $e]);
                } else {
                    Log::warning($e->getMessage());
                }

                return response()->json($response, $statusCode);
            }
        });
    })->create();
