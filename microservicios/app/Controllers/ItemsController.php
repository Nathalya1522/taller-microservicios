<?php

namespace App\Controllers;

use App\Models\ItemRetro;
use Exception;

class ItemsController
{
    function getItems($retrospectivaId)
    {
        $items = ItemRetro::where('retrospectiva_id', $retrospectivaId)->get();
        if ($items->isEmpty()) {
            throw new Exception("No hay items para la retrospectiva $retrospectivaId", 2);
        }
        return $items;
    }

    function getItem($id)
    {
        $item = ItemRetro::find($id);
        if (empty($item)) {
            throw new Exception("Item $id no existe", 2);
        }
        return $item;
    }

    function guardarItem($retrospectivaId, $data)
    {
        if (empty($data['tipo']) || empty($data['descripcion'])) {
            throw new Exception("Faltan datos obligatorios", 1);
        }
        $tiposValidos = ['logro', 'impedimento', 'accion'];
        if (!in_array($data['tipo'], $tiposValidos)) {
            throw new Exception("Tipo invalido. Use: logro, impedimento o accion", 1);
        }
        $item = new ItemRetro();
        $item->retrospectiva_id = $retrospectivaId;
        $item->tipo             = $data['tipo'];
        $item->descripcion      = $data['descripcion'];
        $item->estado           = empty($data['estado']) ? 'pendiente' : $data['estado'];
        $item->save();
        return $item;
    }

    function modificarItem($id, $data)
    {
        $item = $this->getItem($id);
        $item->tipo        = $data['tipo'];
        $item->descripcion = $data['descripcion'];
        $item->estado      = empty($data['estado']) ? $item->estado : $data['estado'];
        $item->save();
        return $item;
    }

    function borrarItem($id)
    {
        $item = $this->getItem($id);
        $item->delete();
        return true;
    }
}