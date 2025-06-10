<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Muestra la lista de usuarios.
     */
    public function index()
    {
        $users = User::all();
        return view('admin.usuarios', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidoP' => 'required|string|max:100',
            'apellidoM' => 'required|string|max:100',
            'correo' => 'required|email|unique:users,correo',
            'password' => 'required|string|min:8',
            'rol' => 'required|in:Administrador,Cliente,Vendedor',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'apellidoP' => $request->apellidoP,
            'apellidoM' => $request->apellidoM,
            'correo' => $request->correo,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * Muestra los detalles de un usuario específico.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    /**
     * Actualiza un usuario existente en la base de datos.
     */
    public function update(Request $request, $id)
{
    $request->validate([
        'nombre' => 'required|string|max:100',
        'apellidoP' => 'required|string|max:100',
        'apellidoM' => 'required|string|max:100',
        'rol' => 'required|in:Administrador,Cliente,Vendedor',
    ]);

    $user = User::findOrFail($id);
    $user->update([
        'nombre' => $request->nombre,
        'apellidoP' => $request->apellidoP,
        'apellidoM' => $request->apellidoM,
        'rol' => $request->rol,
    ]);

    return response()->json(['success' => true]);
}

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['success' => true]);
    }
    
}