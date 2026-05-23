<?php

namespace App\Repositories;

use App\Controllers\SprintsController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class SprintsRepository
{
    function getAll(Request $request, Response $response)
    {
        $controller = new SprintsController();
        $sprints = $controller->getSprints();
        $response->getBody()->write($sprints->toJson());
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    function detail(Request $request, Response $response, $args)
    {
        try {
            $controller = new SprintsController();
            $sprint = $controller->getSprint($args['id']);
            $response->getBody()->write($sprint->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Sprint no encontrado']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new SprintsController();
            $sprint = $controller->guardarSprint($data);
            $response->getBody()->write($sprint->toJson());
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
            $controller = new SprintsController();
            $sprint = $controller->modificarSprint($args['id'], $data);
            $response->getBody()->write($sprint->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Sprint no encontrado']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    function delete(Request $request, Response $response, $args)
    {
        try {
            $controller = new SprintsController();
            $controller->borrarSprint($args['id']);
            $response->getBody()->write(json_encode(['msg' => 'Sprint eliminado']));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Sprint no encontrado']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}