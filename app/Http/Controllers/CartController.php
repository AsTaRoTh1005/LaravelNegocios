<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    /**
     * Obtiene el carrito validado desde la sesión
     */
    private function getValidCart()
    {
        $cart = session()->get('cart');
        
        // Si el carrito es string (p.ej. serializado), convertirlo a array
        if (is_string($cart)) {
            try {
                $cart = json_decode($cart, true, 512, JSON_THROW_ON_ERROR);
                if (!is_array($cart)) {
                    throw new \Exception('Formato inválido');
                }
            } catch (\Exception $e) {
                Log::error('Carrito corrupto: '.$e->getMessage());
                $cart = [];
            }
        }
        
        return is_array($cart) ? $cart : [];
    }

    /**
     * Muestra el contenido del carrito
     */
    public function index()
    {
        $cart = $this->getValidCart();
        $productos = [];
        $total = 0;

        foreach ($cart as $id => $item) {
            // Validar estructura de cada item
            if (!isset($item['cantidad']) || !isset($item['precio'])) {
                Log::warning("Item de carrito inválido: $id");
                continue;
            }

            $producto = Producto::with('negocio')->find($id);
            
            if ($producto) {
                $subtotal = $producto->precio * $item['cantidad'];
                
                $productos[] = [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'precio' => $producto->precio,
                    'imagen' => $producto->imagen,
                    'negocio' => $producto->negocio->nombre,
                    'cantidad' => $item['cantidad'],
                    'subtotal' => $subtotal
                ];
                
                $total += $subtotal;
            } else {
                Log::warning("Producto no encontrado en carrito: $id");
                unset($cart[$id]); // Limpiar producto inexistente
            }
        }

        // Actualizar carrito por si hubo limpieza
        session()->put('cart', $cart);

        return view('cliente.carrito', compact('productos', 'total'));
    }

    /**
     * Añade un producto al carrito
     */
    public function add(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:productos,id',
            'cantidad' => 'required|integer|min:1'
        ]);

        $producto = Producto::findOrFail($validated['id']);
        
        // Verificar stock disponible
        if ($producto->stock < $validated['cantidad']) {
            return back()->with('error', "No hay suficiente stock. Disponible: {$producto->stock} unidades");
        }

        $cart = $this->getValidCart();

        // Actualizar cantidad si el producto ya está en el carrito
        if (isset($cart[$producto->id])) {
            $nuevaCantidad = $cart[$producto->id]['cantidad'] + $validated['cantidad'];
            
            // Verificar nuevamente el stock con la cantidad acumulada
            if ($producto->stock < $nuevaCantidad) {
                return back()->with('error', "No hay suficiente stock para la cantidad solicitada");
            }
            
            $cart[$producto->id]['cantidad'] = $nuevaCantidad;
        } else {
            $cart[$producto->id] = [
                "cantidad" => $validated['cantidad'],
                "precio" => $producto->precio
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', "{$producto->nombre} añadido al carrito");
    }

    /**
     * Actualiza la cantidad de un producto en el carrito
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cantidad' => 'required|integer|min:1'
        ]);

        $cart = $this->getValidCart();
        
        if (!isset($cart[$id])) {
            return back()->with('error', 'Producto no encontrado en el carrito');
        }

        $producto = Producto::findOrFail($id);
        
        if ($producto->stock < $request->cantidad) {
            return back()->with('error', "Stock insuficiente. Máximo disponible: {$producto->stock}");
        }

        $cart[$id]['cantidad'] = $request->cantidad;
        session()->put('cart', $cart);

        return back()->with('success', 'Cantidad actualizada');
    }

    /**
     * Elimina un producto del carrito
     */
    public function remove($id)
    {
        $cart = $this->getValidCart();
        
        if (!isset($cart[$id])) {
            return back()->with('error', 'Producto no encontrado en el carrito');
        }

        unset($cart[$id]);
        session()->put('cart', $cart);

        return back()->with('success', 'Producto eliminado del carrito');
    }

    /**
     * Vacía completamente el carrito
     */
    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Carrito vaciado correctamente');
    }
}