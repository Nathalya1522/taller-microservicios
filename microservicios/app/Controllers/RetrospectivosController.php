<?php

namespace App\Controllers;

use App\Models\Retrospectiva;
use Exception;

class RetrospectivosController
{
    function getRetrospectivas()
    {
        return Retrospectiva::all();
    }

    function getRetrospectiva($id)
    {
        $retrospectiva = Retrospectiva::find($id);
        if (empty($retrospectiva)) {
            throw new Exception("Retrospectiva $id no existe", 2);
        }
        return $retrospectiva;
    }

    function guardarRetrospectiva($data)
    {
        if (empty($data['sprint_numero']) || empty($data['sprint_nombre']) || empty($data['fecha'])) {
            throw new Exception("Faltan datos obligatorios", 1);
        }
        $retrospectiva = new Retrospectiva();
        $retrospectiva->sprint_numero = $data['sprint_numero'];
        $retrospectiva->sprint_nombre = $data['sprint_nombre'];
        $retrospectiva->fecha         = $data['fecha'];
        $retrospectiva->descripcion   = empty($data['descripcion']) ? null : $data['descripcion'];
        $retrospectiva->save();
        return $retrospectiva;
    }

    function modificarRetrospectiva($id, $data)
    {
        $retrospectiva = $this->getRetrospectiva($id);
        $retrospectiva->sprint_numero = $data['sprint_numero'];
        $retrospectiva->sprint_nombre = $data['sprint_nombre'];
        $retrospectiva->fecha         = $data['fecha'];
        $retrospectiva->descripcion   = empty($data['descripcion']) ? null : $data['descripcion'];
        $retrospectiva->save();
        return $retrospectiva;
    }

    function borrarRetrospectiva($id)
    {
        $retrospectiva = $this->getRetrospectiva($id);
        $retrospectiva->delete();
        return true;
    }
}