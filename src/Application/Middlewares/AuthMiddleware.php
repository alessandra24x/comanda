<?php

namespace App\Middlewares;

use App\Application\Models\Usuario;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Helpers\JwtHelper;

class AuthMiddleware
{

    public function __construct($tipo)
    {
        $this->tipo = $tipo;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $token = $request->getHeaderLine('token');
        //$payload = null;
        try {
            $payload = JwtHelper::validatorJWT($token);
        } catch (\Exception $e) {
            $response = new Response();
            $response->withStatus(401);
            return $response;
        }

        $user = Usuario::firstWhere('email', $payload->email);
        //        $response = new Response();
        //        $response->getBody()->write(json_encode($payload->tipo));
        //        return $response;
        $valido = ($user && $this->tipo === $user->tipo);

        if (!$valido) {

            $response = new Response();
            $response->getBody()->write('Prohibido pasar');
            // throw new \Slim\Exception\HttpForbiddenException( $request );
            return $response->withStatus(403);
        } else { // Este else está medio al pelo

            $response = $handler->handle($request);
            $existingContent = (string) $response->getBody();
            $resp = new Response();
            $resp->getBody()->write($existingContent);

            return $resp;
        }
    }
}
