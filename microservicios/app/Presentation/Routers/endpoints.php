<?php

use App\Repositories\SprintsRepository;
use App\Repositories\HistoriasRepository;
use Slim\App;

return function (App $app) {

    $app->get("/sprints", function ($req, $res) {
        return (new SprintsRepository())->getAll($req, $res);
    });
    $app->post("/sprint", function ($req, $res) {
        return (new SprintsRepository())->create($req, $res);
    });
    $app->get("/sprint/{id}", function ($req, $res, $args) {
        return (new SprintsRepository())->detail($req, $res, $args);
    });
    $app->put("/sprint/{id}", function ($req, $res, $args) {
        return (new SprintsRepository())->update($req, $res, $args);
    });
    $app->delete("/sprint/{id}", function ($req, $res, $args) {
        return (new SprintsRepository())->delete($req, $res, $args);
    });

    $app->get("/historias", function ($req, $res) {
        return (new HistoriasRepository())->getAll($req, $res);
    });
    $app->get("/sprint/{id}/historias", function ($req, $res, $args) {
        return (new HistoriasRepository())->listPorSprint($req, $res, $args);
    });
    $app->post("/historia", function ($req, $res) {
        return (new HistoriasRepository())->create($req, $res);
    });
    $app->get("/historia/{id}", function ($req, $res, $args) {
        return (new HistoriasRepository())->detail($req, $res, $args);
    });
    $app->put("/historia/{id}", function ($req, $res, $args) {
        return (new HistoriasRepository())->update($req, $res, $args);
    });
    $app->delete("/historia/{id}", function ($req, $res, $args) {
        return (new HistoriasRepository())->delete($req, $res, $args);
    });
};
