<?php

declare(strict_types=1);

use App\Application\Actions\User\ViewUserAction;
use App\Application\Controllers\EmpleadoController;
use App\Application\Controllers\MesaController;
use App\Application\Controllers\PedidoController;
use App\Application\Middlewares\AuthMiddleware;
use App\Application\Middlewares\JsonMiddleware;
use App\Application\Models\Pedido;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Slim\Routing\RouteCollectorProxy;

const ARRAY_ROLES = [ 'socio', 'cocina', 'barra', 'cerveza', 'mozo'];

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write(json_encode('Hello world!'));
        return $response;
    });

    //REGISTRO
    $app->post('/registro', EmpleadoController::class . ':addOne');

    //LOGIN
    $app->post('/login', EmpleadoController::class . ':login');

    //MESA
    $app->group('/mesa', function (RouteCollectorProxy $group) {
        $group->post('[/]', MesaController::class . ':addOne')->add(new AuthMiddleware('socio'));
        $group->get('[/]', MesaController::class. ':getAll');
        $group->put('/{codigo}', MesaController::class . ':cambioEstado')->add(new AuthMiddleware('mozo'));
        $group->put('/cierre/{codigo}', MesaController::class . ':cerrarMesa')->add(new AuthMiddleware('socio'));
    });

    //PEDIDO
    $app->group('/pedido', function (RouteCollectorProxy $group) {
        $group->post('[/]', PedidoController::class . ':addOne');
        $group->put('/{codigo}', PedidoController::class . ':updateOne');
        $group->get('[/]', PedidoController::class . ':getAll')->add(new AuthMiddleware('socio'));
        $group->get('/{id}', PedidoController::class . ':getOne');
    });


};
