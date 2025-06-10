<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Negocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // Obtener solo los productos del negocio del vendedor autenticado
        $negocio = Negocio::where('id_usuario', Auth::id())->first();
        
        if (!$negocio) {
            return redirect()->back()->with('error', 'No tienes un negocio registrado');
        }

        $productos = $negocio->productos;
        return view('vendedor.productos', compact('productos', 'negocio'));
    }

    public function store(Request $request)
    {
        $negocio = Negocio::where('id_usuario', Auth::id())->first();

        if (!$negocio) {
            return response()->json([
                'success' => false,
                'message' => 'No tienes un negocio registrado'
            ], 400);
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $imagenPath = null;
            if ($request->hasFile('imagen')) {
                $imagenPath = $request->file('imagen')->store('productos', 'public');
            }

            $producto = $negocio->productos()->create([
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'stock' => $validated['stock'],
                'imagen' => $imagenPath
            ]);

            return response()->json([
                'success' => true,
                'producto' => $producto
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $producto = Producto::with('negocio')
            ->whereHas('negocio', function($query) {
                $query->where('id_usuario', Auth::id());
            })
            ->findOrFail($id);

        return response()->json($producto);
    }

    public function update(Request $request, $id)
    {
        $producto = Producto::with('negocio')
            ->whereHas('negocio', function($query) {
                $query->where('id_usuario', Auth::id());
            })
            ->findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $data = [
                'nombre' => $validated['nombre'],
                'descripcion' => $validated['descripcion'],
                'precio' => $validated['precio'],
                'stock' => $validated['stock']
            ];

            if ($request->hasFile('imagen')) {
                // Eliminar imagen anterior si existe
                if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                    Storage::disk('public')->delete($producto->imagen);
                }
                $data['imagen'] = $request->file('imagen')->store('productos', 'public');
            }

            $producto->update($data);

            return response()->json([
                'success' => true,
                'producto' => $producto->fresh()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $producto = Producto::with('negocio')
            ->whereHas('negocio', function($query) {
                $query->where('id_usuario', Auth::id());
            })
            ->findOrFail($id);

        try {
            // Eliminar imagen asociada si existe
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            $producto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el producto: ' . $e->getMessage()
            ], 500);
        }
    }
}