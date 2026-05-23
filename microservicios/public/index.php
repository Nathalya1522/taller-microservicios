<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Config/database.php';

$cors      = require __DIR__ . '/../app/Presentation/Middlewares/CorsMiddleware.php';
$endpoints = require __DIR__ . '/../app/Routers/endpoints.php';

$app = AppFactory::create();
$app->addBodyParsingMiddleware();   // ← necesario para leer JSON del body
$app->addRoutingMiddleware();
$app->addErrorMiddleware(true, true, true);

$cors($app);
$endpoints($app);

$app->run();