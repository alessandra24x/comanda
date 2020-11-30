<?php

namespace App\Application\Middlewares;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

class JsonMiddleware
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        // Agrego un middleware para agregarle en el header el application/json
        $response = $handler->handle($request);
        $response = $response->withHeader('Content-type', 'application/json');

        return $response;
    }
}
