<?php

namespace App\Application\Controllers;

use App\Application\Models\Usuario;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Helpers\JwtHelper;

class UsuarioController
{
    public function getAll(Request $request, Response $response)
    {
        $rta = Usuario::get();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function getOne(Request $request, Response $response, $args)
    {
        $rta = Usuario::find($args['id']);
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function addOne(Request $request, Response $response, $args)
    {
        $params = (array) $request->getParsedBody();

        if (Usuario::where('email', '=', $params['email'])->get()->first() != null) {
            $response->getBody()->write('Ya existe un usuario con ese email');
            return $response->withStatus(404);
        }
        if (Usuario::where('nombre', '=', $params['nombre'])->get()->first() != null) {
            $response->getBody()->write('Ya existe un usuario con ese nombre');
            return $response->withStatus(404);
        }

        $user = Usuario::create($params);
        $rta = $user->save();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function updateOne(Request $request, Response $response, $args)
    {
        $params = (array) $request->getParsedBody();
        $user = Usuario::firstWhere('legajo', $params['legajo']);
        $user->legajo = $args['legajo'];
        $rta = $user->save();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function deleteOne(Request $request, Response $response, $args)
    {
        $user = Usuario::find(10);
        $rta = $user->delete();
        $response->getBody()->write(json_encode($rta));
        return $response;
    }

    public function login(Request $request, Response $response)
    {
        $msgError = 'clave o email invalido';

        if ($request === null) {
            $response->withStatus(401);
            $response->getBody()->write($msgError);
            return $response;
        }

        $params = (array) $request->getParsedBody();
        $clave = $params['clave'];
        $email = $params['email'];

        $user = Usuario::firstWhere('email', $email);
        if (!$user || $user->clave !== $clave) {
            $response->withStatus(401);
            $response->getBody()->write($msgError);
            return $response;
        }

        $payload = array("email" => $email, "tipo" => $user->tipo, "id" => $user->id);
        //        $response->getBody()->write(json_encode($payload));
        //        return $response;
        $response->getBody()->write(json_encode(JwtHelper::createJWT($payload)));
        return $response;
    }
}
