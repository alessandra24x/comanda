<?php

namespace App\Application\Controllers;

use App\Application\Helpers\JwtHelper;
use App\Application\Models\Empleado;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class EmpleadoController extends Controller
{
    public function getAll(Request $request, Response $response)
    {
        $rta = Empleado::get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public static function getOne(Request $request, Response $response, $args)
    {
        $rta = Empleado::find($args['id']);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function addOne(Request $request, Response $response, $args)
    {
        $user = new Empleado;
        $user['nombre'] = $request->getParsedBody()['nombre'] ?? null;
        $user['sector'] = $request->getParsedBody()['sector'] ?? null;
        $user['password'] = $request->getParsedBody()['password'] ?? null;

        if (!$user->validate() || !$user->save()) {
            $this->respondWithData($response, "datos invalidos", 400);
            return $response;
        }

        $this->respondWithData($response, $user);

        return $response;
    }



    public function login(Request $request, Response $response)
    {
        $msgError = 'clave o user invalido';

        if ($request === null) {
            $this->respondWithData($response, $msgError, 401);
            return $response;
        }

        $params = (array) $request->getParsedBody();
        $nombre = $params['nombre'] ?? null;
        $password = $params['password'] ?? null;
        $user = Empleado::firstWhere('nombre', $nombre);

        if (!$user || $user->Password !== $password) {
            $this->respondWithData($response, $msgError, 401);
            return $response;
        }

        $payload = array("nombre" => $nombre, "id" => $user->Id, "sector" => $user->Sector);

        $this->respondWithData($response, JwtHelper::createJWT($payload));
        return $response;
    }

    public static function getEmpleado($dato)
    {
        $empleado = Empleado::where('empleado', $dato)->first();
        return $empleado;
    }
}
