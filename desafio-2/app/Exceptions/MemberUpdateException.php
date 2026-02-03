<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class MemberUpdateException extends HttpException
{
    public function __construct(string $message = 'Não foi possível atualizar o cadastro do associado no momento. Tente novamente.', ?\Throwable $previous = null)
    {
        parent::__construct(500, $message, $previous);
    }
}
