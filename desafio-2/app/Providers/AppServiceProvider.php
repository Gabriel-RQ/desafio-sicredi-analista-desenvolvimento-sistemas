<?php

namespace App\Providers;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MemberController;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\Operation;
use Dedoc\Scramble\Support\Generator\Response;
use Dedoc\Scramble\Support\Generator\Schema;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
use Dedoc\Scramble\Support\Generator\Types\BooleanType;
use Dedoc\Scramble\Support\Generator\Types\ObjectType;
use Dedoc\Scramble\Support\Generator\Types\StringType;
use Dedoc\Scramble\Support\RouteInfo;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Scramble::configure()
            ->withOperationTransformers(function (Operation $operation, RouteInfo $routeInfo) {

                // Adiciona resposta 401 Não autorizado em todas as rotas do controlador de Associado e nas rotas de logout e login na documentação OpenAPI

                $isMemberController = $routeInfo->className() === MemberController::class;

                $isLogoutRoute = $routeInfo->className() === AuthController::class && ($routeInfo->methodName() === 'logout' || $routeInfo->methodName() === 'login');

                if (! $isMemberController && ! $isLogoutRoute) {
                    return;
                }

                $errorType = new ObjectType;
                $errorType->addProperty('success', new BooleanType()->default(false));
                $errorType->addProperty('message', new StringType);

                $operation->addResponse(
                    Response::make(401)
                        ->description('Não autenticado ou não autorizado')
                        ->setContent(
                            'application/json',
                            Schema::fromType($errorType)
                        )
                );
            })
            ->withDocumentTransformers(function (OpenApi $openApi) {
                $openApi->secure(
                    SecurityScheme::http('bearer', 'JWT')
                );
            });
    }
}
