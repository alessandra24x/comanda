<?php

declare(strict_types=1);

use App\Application\Middlewares\JsonMiddleware;
use Slim\App;

return function (App $app) {
    $app->add(JsonMiddleware::class);
};
