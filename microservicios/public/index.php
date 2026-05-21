<?php

ob_start();
require_once __DIR__ . '/../app/presentation/middlewares/CorsMiddleware.php';
require_once __DIR__ . '/../app/presentation/routers/endpoints.php';

ob_clean();
Middleware::aplicar();

$router = new Router();
$router->resolver();