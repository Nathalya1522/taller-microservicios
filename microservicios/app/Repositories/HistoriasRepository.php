<?php

namespace App\Repositories;

use App\Controllers\HistoriasController;
use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class HistoriasRepository
{
    public function getAll(Request $request, Response $response)
    {
        $controller = new HistoriasController();
        $historias = $controller->getHistorias();
        $response->getBody()->write($historias->toJson());
        return $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json');
    }

    public function listPorSprint(Request $request, Response $response, $args)
    {
        try {
            $controller = new HistoriasController();
            $historias = $controller->getHistoriasPorSprint($args['id']);
            $response->getBody()->write($historias->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'No hay historias para este sprint']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function detail(Request $request, Response $response, $args)
    {
        try {
            $controller = new HistoriasController();
            $historia = $controller->getHistoria($args['id']);
            $response->getBody()->write($historia->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Historia no encontrada']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function create(Request $request, Response $response)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new HistoriasController();
            $historia = $controller->guardarHistoria($data);
            $response->getBody()->write($historia->toJson());
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

    public function update(Request $request, Response $response, $args)
    {
        try {
            $body = $request->getBody()->getContents();
            $data = json_decode($body, true);
            $controller = new HistoriasController();
            $historia = $controller->modificarHistoria($args['id'], $data);
            $response->getBody()->write($historia->toJson());
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Historia no encontrada']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }

    public function delete(Request $request, Response $response, $args)
    {
        try {
            $controller = new HistoriasController();
            $controller->borrarHistoria($args['id']);
            $response->getBody()->write(json_encode(['msg' => 'Historia eliminada']));
            return $response
                ->withStatus(200)
                ->withHeader('Content-Type', 'application/json');
        } catch (Exception $ex) {
            $response->getBody()->write(json_encode(['msg' => 'Historia no encontrada']));
            return $response
                ->withStatus(404)
                ->withHeader('Content-Type', 'application/json');
        }
    }
}