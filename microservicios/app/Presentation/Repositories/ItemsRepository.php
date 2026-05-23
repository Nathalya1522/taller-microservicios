<?php

namespace App\Repositories;

use App\Controllers\ItemsController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ItemsRepository
{
    function list(Request $request, Response $response, $args)
    {
        try {
            $controller = new ItemsController();
            $items = $controller->getItems($args['id']);
            $response->getBody()->write($items->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Retrospectiva no encontrada']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function create(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new ItemsController();
            $item = $controller->guardarItem($args['id'], $data);
            $response->getBody()->write($item->toJson());
            return $response
                ->withStatus(201)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $code = 400;
            if ($ex->getCode() == 1) {
                $code = 406;
                $response->getBody()->write(json_encode(['msg' => 'Datos incorrectos o incompletos']));
            } else {
                $response->getBody()->write(json_encode(['msg' => 'Error en el servicio']));
            }
            return $response
                ->withStatus($code)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function update(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new ItemsController();
            $item = $controller->modificarItem($args['id'], $data);
            $response->getBody()->write($item->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Item no encontrado']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function delete(Request $request, Response $response, $args)
    {
        try {
            $controller = new ItemsController();
            $controller->borrarItem($args['id']);
            $response->getBody()->write(json_encode(['msg' => 'Item eliminado']));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Item no encontrado']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}