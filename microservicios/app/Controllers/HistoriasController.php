<?php

namespace App\Controllers;

use App\Models\Historia;
use Exception;

class HistoriasController
{
    function getHistorias()
    {
        return Historia::all();
    }

    function getHistoriasPorSprint($sprintId)
    {
        $historias = Historia::where('sprint_id', $sprintId)->get();
        if ($historias->isEmpty()) {
            throw new Exception("No hay historias para el sprint $sprintId", 2);
        }
        return $historias;
    }

    function getHistoria($id)
    {
        $historia = Historia::find($id);
        if (empty($historia)) {
            throw new Exception("Historia $id no existe", 2);
        }
        return $historia;
    }

    function guardarHistoria($data)
    {
        if (empty($data['titulo']) || empty($data['descripcion']) || empty($data['responsable']) || empty($data['puntos']) || empty($data['fecha_creacion']) || empty($data['sprint_id'])) {
            throw new Exception("Faltan datos obligatorios", 1);
        }
        $estadosValidos = ['nueva', 'activa', 'finalizada', 'impedimento'];
        if (!empty($data['estado']) && !in_array($data['estado'], $estadosValidos)) {
            throw new Exception("Estado invalido. Use: nueva, activa, finalizada o impedimento", 1);
        }
        $historia = new Historia();
        $historia->titulo             = $data['titulo'];
        $historia->descripcion        = $data['descripcion'];
        $historia->responsable        = $data['responsable'];
        $historia->estado             = empty($data['estado']) ? 'nueva' : $data['estado'];
        $historia->puntos             = $data['puntos'];
        $historia->fecha_creacion     = $data['fecha_creacion'];
        $historia->fecha_finalizacion = empty($data['fecha_finalizacion']) ? null : $data['fecha_finalizacion'];
        $historia->sprint_id          = $data['sprint_id'];
        $historia->save();
        return $historia;
    }

    function modificarHistoria($id, $data)
    {
        $historia = $this->getHistoria($id);
        $historia->titulo             = $data['titulo'];
        $historia->descripcion        = $data['descripcion'];
        $historia->responsable        = $data['responsable'];
        $historia->estado             = empty($data['estado']) ? $historia->estado : $data['estado'];
        $historia->puntos             = $data['puntos'];
        $historia->fecha_creacion     = $data['fecha_creacion'];
        $historia->fecha_finalizacion = empty($data['fecha_finalizacion']) ? null : $data['fecha_finalizacion'];
        $historia->sprint_id          = $data['sprint_id'];
        $historia->save();
        return $historia;
    }

    function borrarHistoria($id)
    {
        $historia = $this->getHistoria($id);
        $historia->delete();
        return true;
    }
}