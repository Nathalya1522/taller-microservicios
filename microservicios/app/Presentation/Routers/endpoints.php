<?php

use App\Controllers\RetrospectivosController;
use App\Controllers\ItemsController;
use App\Controllers\SeguimientoController;

return function ($app) {

    // ─── RETROSPECTIVAS ───────────────────────────────────────────
    $app->get('/retrospectivas', function ($request, $response) {
        $controller = new RetrospectivosController();
        $data = $controller->getRetrospectivas();
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/retrospectivas/{id}', function ($request, $response, $args) {
        $controller = new RetrospectivosController();
        $data = $controller->getRetrospectiva($args['id']);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/retrospectivas', function ($request, $response) {
        $controller = new RetrospectivosController();
        $data = $request->getParsedBody();
        $result = $controller->guardarRetrospectiva($data);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $app->put('/retrospectivas/{id}', function ($request, $response, $args) {
        $controller = new RetrospectivosController();
        $data = $request->getParsedBody();
        $result = $controller->modificarRetrospectiva($args['id'], $data);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->delete('/retrospectivas/{id}', function ($request, $response, $args) {
        $controller = new RetrospectivosController();
        $result = $controller->borrarRetrospectiva($args['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ─── ITEMS (logros, impedimentos, acciones) ───────────────────
    $app->get('/retrospectivas/{id}/items', function ($request, $response, $args) {
        $controller = new ItemsController();
        $data = $controller->getItems($args['id']);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/retrospectivas/{id}/items', function ($request, $response, $args) {
        $controller = new ItemsController();
        $data = $request->getParsedBody();
        $result = $controller->guardarItem($args['id'], $data);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $app->put('/items/{id}', function ($request, $response, $args) {
        $controller = new ItemsController();
        $data = $request->getParsedBody();
        $result = $controller->modificarItem($args['id'], $data);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->delete('/items/{id}', function ($request, $response, $args) {
        $controller = new ItemsController();
        $result = $controller->borrarItem($args['id']);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });

    // ─── SEGUIMIENTO DE ACCIONES ──────────────────────────────────
    $app->get('/retrospectivas/{id}/acciones-anteriores', function ($request, $response, $args) {
        $controller = new SeguimientoController();
        $data = $controller->getAccionesAnteriores($args['id']);
        $response->getBody()->write(json_encode($data));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/seguimiento', function ($request, $response) {
        $controller = new SeguimientoController();
        $data = $request->getParsedBody();
        $result = $controller->guardarSeguimiento($data);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    });

    $app->put('/seguimiento/{id}', function ($request, $response, $args) {
        $controller = new SeguimientoController();
        $data = $request->getParsedBody();
        $result = $controller->modificarSeguimiento($args['id'], $data);
        $response->getBody()->write(json_encode($result));
        return $response->withHeader('Content-Type', 'application/json');
    });
};