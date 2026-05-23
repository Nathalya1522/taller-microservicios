<?php

namespace App\Repositories;

use App\Controllers\RetrospectivosController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RetrospectivosRepository
{
    function list(Request $request, Response $response)
    {
        $controller = new RetrospectivosController();
        $retrospectivas = $controller->getRetrospectivas();
        $response->getBody()->write($retrospectivas->toJson());
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    function detail(Request $request, Response $response, $args)
    {
        try {
            $controller = new RetrospectivosController();
            $retrospectiva = $controller->getRetrospectiva($args['id']);
            $response->getBody()->write($retrospectiva->toJson());
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

    function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new RetrospectivosController();
            $retrospectiva = $controller->guardarRetrospectiva($data);
            $response->getBody()->write($retrospectiva->toJson());
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
            $controller = new RetrospectivosController();
            $retrospectiva = $controller->modificarRetrospectiva($args['id'], $data);
            $response->getBody()->write($retrospectiva->toJson());
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

    function delete(Request $request, Response $response, $args)
    {
        try {
            $controller = new RetrospectivosController();
            $controller->borrarRetrospectiva($args['id']);
            $response->getBody()->write(json_encode(['msg' => 'Retrospectiva eliminada']));
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
}