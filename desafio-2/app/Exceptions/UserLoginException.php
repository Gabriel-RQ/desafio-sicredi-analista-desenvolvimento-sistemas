<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class UserLoginException extends HttpException
{
    public function __construct(string $message = 'Usuário não autorizado', ?\Throwable $previous = null)
    {
        parent::__construct(401, $message, $previous);
    }
}
