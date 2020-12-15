<?php

namespace App\Application\Middlewares;

use _HumbugBoxf99c1794c57d\Nette\Neon\Exception;
use App\Application\Controllers\Controller;
use App\Application\Models\Empleado;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use App\Application\Helpers\JwtHelper;

class AuthMiddleware extends Controller
{

    /**
     * @var mixed|null
     */
    private $sector;

    public function __construct($sector = null)
    {
        $this->sector = $sector;
    }

    public function __invoke(Request $request, RequestHandler $handler)
    {
        $token = $request->getHeaderLine('token');
        try {
            $payload = JwtHelper::validatorJWT($token);
        } catch (\Exception $e) {
            $response = new Response();
            $response->getBody()->write("se te olvido pasar el token");
            return $response->withStatus(400);
        }

        $user = Empleado::firstWhere('nombre', $payload->nombre);
        //        $response = new Response();
        //        $response->getBody()->write(json_encode($payload->tipo));
        //        return $response;
        
        $valido = ($user && $this->sector === $user->Sector);

        if (!$valido) {
            $response = new Response();
            return $this->respondWithData($response, 'Datos invalidos. Asegurese de tener los permisos necesarios', 403);
        }

        $response = $handler->handle($request);

        return $response;

    }
}
