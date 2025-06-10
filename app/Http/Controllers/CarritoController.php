<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class CarritoController extends Controller
{
    public function agregar(Request $request)
    {
        $request->validate([
            'producto_id' => 'required|exists:productos,id',
            'cantidad' => 'required|numeric|min:1'
        ]);

        $producto = Producto::findOrFail($request->producto_id);
        
        \Cart::add([
            'id' => $producto->id,
            'name' => $producto->nombre,
            'price' => $producto->precio,
            'quantity' => $request->cantidad,
            'attributes' => [
                'imagen' => $producto->imagen,
                'stock' => $producto->stock,
                'negocio_id' => $producto->negocio_id
            ]
        ]);

        return redirect()->back()->with('success', 'Producto agregado al carrito');
    }

    public function mostrar()
    {
        $items = \Cart::getContent();
        return view('cliente.carrito', compact('items'));
    }

    public function actualizar(Request $request, $itemId)
    {
        $request->validate([
            'cantidad' => 'required|numeric|min:1'
        ]);

        \Cart::update($itemId, [
            'quantity' => [
                'relative' => false,
                'value' => $request->cantidad
            ]
        ]);

        return redirect()->back()->with('success', 'Carrito actualizado');
    }

    public function eliminar($itemId)
    {
        \Cart::remove($itemId);
        return redirect()->back()->with('success', 'Producto eliminado del carrito');
    }
}