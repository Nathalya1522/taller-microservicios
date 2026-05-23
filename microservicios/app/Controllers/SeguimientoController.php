<?php

namespace App\Controllers;

use App\Models\SeguimientoAccion;
use App\Models\ItemRetro;
use Exception;

class SeguimientoController
{
    function getAccionesAnteriores($retrospectivaId)
    {
        // Trae las acciones de la retrospectiva anterior
        $acciones = ItemRetro::where('tipo', 'accion')
            ->where('retrospectiva_id', '<', $retrospectivaId)
            ->orderBy('retrospectiva_id', 'desc')
            ->get();

        if ($acciones->isEmpty()) {
            throw new Exception("No hay acciones anteriores", 2);
        }
        return $acciones;
    }

    function getSeguimiento($id)
    {
        $seguimiento = SeguimientoAccion::find($id);
        if (empty($seguimiento)) {
            throw new Exception("Seguimiento $id no existe", 2);
        }
        return $seguimiento;
    }

    function guardarSeguimiento($data)
    {
        if (empty($data['accion_id']) || empty($data['retrospectiva_id'])) {
            throw new Exception("Faltan datos obligatorios", 1);
        }
        $seguimiento = new SeguimientoAccion();
        $seguimiento->accion_id        = $data['accion_id'];
        $seguimiento->retrospectiva_id = $data['retrospectiva_id'];
        $seguimiento->cumplida         = empty($data['cumplida']) ? 0 : $data['cumplida'];
        $seguimiento->observacion      = empty($data['observacion']) ? null : $data['observacion'];
        $seguimiento->save();
        return $seguimiento;
    }

    function modificarSeguimiento($id, $data)
    {
        $seguimiento = $this->getSeguimiento($id);
        $seguimiento->cumplida    = isset($data['cumplida']) ? $data['cumplida'] : $seguimiento->cumplida;
        $seguimiento->observacion = empty($data['observacion']) ? $seguimiento->observacion : $data['observacion'];
        $seguimiento->save();
        return $seguimiento;
    }
}