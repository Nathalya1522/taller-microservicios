<?php

use App\Repositories\RetrospectivosRepository;
use App\Repositories\ItemsRepository;
use App\Repositories\SeguimientoRepository;
use Slim\App;
use Slim\Routing\RouteCollectorProxy;

return function (App $app) {

    // ─── RETROSPECTIVAS ───────────────────────────────────────────
    $app->get('/retrospectivas', [RetrospectivosRepository::class, 'list']);
    $app->post('/retrospectiva', [RetrospectivosRepository::class, 'create']);
    $app->get('/retrospectiva/{id}', [RetrospectivosRepository::class, 'detail']);
    $app->put('/retrospectiva/{id}', [RetrospectivosRepository::class, 'update']);
    $app->delete('/retrospectiva/{id}', [RetrospectivosRepository::class, 'delete']);

    // ─── ITEMS (logros, impedimentos, acciones) ───────────────────
    $app->get('/retrospectiva/{id}/items', [ItemsRepository::class, 'list']);
    $app->post('/retrospectiva/{id}/items', [ItemsRepository::class, 'create']);
    $app->put('/item/{id}', [ItemsRepository::class, 'update']);
    $app->delete('/item/{id}', [ItemsRepository::class, 'delete']);

    // ─── SEGUIMIENTO DE ACCIONES ──────────────────────────────────
    $app->get('/retrospectiva/{id}/acciones-anteriores', [SeguimientoRepository::class, 'accionesAnteriores']);
    $app->post('/seguimiento', [SeguimientoRepository::class, 'create']);
    $app->put('/seguimiento/{id}', [SeguimientoRepository::class, 'update']);

    // ─── AGRUPADO /api ────────────────────────────────────────────
    $app->group('/api', function (RouteCollectorProxy $group) {

        $group->get('/retrospectivas', [RetrospectivosRepository::class, 'list']);
        $group->post('/retrospectiva', [RetrospectivosRepository::class, 'create']);
        $group->get('/retrospectiva/{id}', [RetrospectivosRepository::class, 'detail']);
        $group->put('/retrospectiva/{id}', [RetrospectivosRepository::class, 'update']);
        $group->delete('/retrospectiva/{id}', [RetrospectivosRepository::class, 'delete']);

        $group->get('/retrospectiva/{id}/items', [ItemsRepository::class, 'list']);
        $group->post('/retrospectiva/{id}/items', [ItemsRepository::class, 'create']);
        $group->put('/item/{id}', [ItemsRepository::class, 'update']);
        $group->delete('/item/{id}', [ItemsRepository::class, 'delete']);

        $group->get('/retrospectiva/{id}/acciones-anteriores', [SeguimientoRepository::class, 'accionesAnteriores']);
        $group->post('/seguimiento', [SeguimientoRepository::class, 'create']);
        $group->put('/seguimiento/{id}', [SeguimientoRepository::class, 'update']);
    });
};