<?php

namespace App\Http\Controllers;

use App\Models\Negocio;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class NegocioController extends Controller
{
    public function index()
    {
        $negocios = Negocio::with('usuario')->get();
        $usuarios = User::whereIn('rol', ['Vendedor'])->get(); // Obtiene solo usuarios que pueden ser dueños
        $clientes = User::whereIn('rol', ['Cliente'])->get();
        return view('admin.negocios', compact(['negocios', 'clientes','usuarios']));
    }
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'id_usuario' => 'required|exists:users,id_usuario',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'telefono' => 'required|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Actualizar el rol del usuario a Vendedor
        $usuario = User::findOrFail($request->id_usuario);
        $usuario->rol = 'Vendedor';
        $usuario->save();

        $imagenPath = $request->hasFile('imagen') 
            ? $request->file('imagen')->store('negocios', 'public') 
            : null;

        $negocio = Negocio::create([
            'id_usuario' => $request->id_usuario,
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'direccion' => $request->direccion,
            'telefono' => $request->telefono,
            'imagen' => $imagenPath,
        ]);

        return response()->json($negocio->load('usuario'));
    }

    public function show($id): JsonResponse
    {
        $negocio = Negocio::with(['usuario', 'productos'])->findOrFail($id);
        return response()->json($negocio);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'id_usuario' => 'sometimes|exists:users,id_usuario',
            'nombre' => 'sometimes|string|max:255',
            'descripcion' => 'sometimes|string|max:255',
            'direccion' => 'sometimes|string|max:255',
            'telefono' => 'sometimes|string|max:20',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $negocio = Negocio::findOrFail($id);

        if ($request->hasFile('imagen')) {
            // Eliminar imagen anterior si existe
            if ($negocio->imagen && Storage::disk('public')->exists($negocio->imagen)) {
                Storage::disk('public')->delete($negocio->imagen);
            }
            $imagenPath = $request->file('imagen')->store('negocios', 'public');
            $negocio->imagen = $imagenPath;
        }

        $negocio->update($request->except('imagen'));

        return response()->json($negocio->fresh()->load('usuario'));
    }

    public function destroy($id): JsonResponse
    {
        $negocio = Negocio::findOrFail($id);
        
        // Eliminar imagen asociada si existe
        if ($negocio->imagen && Storage::disk('public')->exists($negocio->imagen)) {
            Storage::disk('public')->delete($negocio->imagen);
        }
        
        $negocio->delete();
        
        return response()->json(['success' => true]);
    }
    public function vendedorShow()
{
    // Obtener el negocio del vendedor autenticado
    $negocio = Negocio::where('id_usuario', auth()->id())->firstOrFail();
    return view('vendedor.negocio', compact('negocio'));
}

public function vendedorUpdate(Request $request)
{
    $negocio = Negocio::where('id_usuario', auth()->id())->firstOrFail();
    
    $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'required|string|max:255',
        'direccion' => 'required|string|max:255',
        'telefono' => 'required|string|max:20',
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($request->hasFile('imagen')) {
        // Eliminar imagen anterior si existe
        if ($negocio->imagen && Storage::disk('public')->exists($negocio->imagen)) {
            Storage::disk('public')->delete($negocio->imagen);
        }
        $imagenPath = $request->file('imagen')->store('negocios', 'public');
        $negocio->imagen = $imagenPath;
    }

    $negocio->update($request->except('imagen'));

    return redirect()->route('vendedor.negocio')->with('success', 'Negocio actualizado correctamente');
}
public function clienteIndex()
{
    $negocios = Negocio::with('usuario')->get();
    return view('cliente.negocios', compact('negocios'));
}

public function getProductosByNegocio(Negocio $negocio)
{
    $productos = $negocio->productos()->where('stock', '>', 0)->get();
    
    return response()->json([
        'success' => true,
        'productos' => $productos->map(function($producto) {
            return [
                'id' => $producto->id, // Aquí usamos id para los productos
                'nombre' => $producto->nombre,
                'descripcion' => $producto->descripcion,
                'precio' => $producto->precio,
                'stock' => $producto->stock,
                'imagen_url' => $producto->imagen ? asset('storage/'.$producto->imagen) : null,
            ];
        })
    ]);
}
public function clienteShow(Negocio $negocio)
{
    // Carga el negocio con sus productos y el usuario dueño
    $negocio->load(['usuario', 'productos' => function($query) {
        $query->where('stock', '>', 0); // Solo productos con stock
    }]);
    $cart = session()->get('cart', []);
    $cartCount = count($cart);
    
    return view('cliente.negocio-show', compact('negocio', 'cartCount'));
}
}