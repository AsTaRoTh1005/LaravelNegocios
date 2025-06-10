<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller {
    public function showLoginForm() {
        return view('auth.login'); 
    }
    public function showRegisterForm() {
        return view('auth.register'); 
    }

    public function adminHome() {
        return view('admin.homeAdmin');
    }

    public function clienteHome() {
        return view('cliente.homeCliente'); 
    }
    public function vendedorHome() {
        return view('vendedor.homeVendedor');
    }
    public function contactanosV() {
        return view('vendedor.contactanos');
    }
    public function sobreNosotros() {
        return view('cliente.sobre-nosotros');
    }
    public function contactanosc() {
        return view('cliente.contactanosC');
    }
    public function mapa() {
        return view('cliente.Mapa');
    }
    
    

    public function login(Request $request) {
        $request->validate([
            'correo' => 'required|email',
            'contraseña' => 'required'
        ]);
    
        $user = User::where('correo', $request->correo)->first();
    
        if ($user && Hash::check($request->contraseña, $user->password)) {
            Auth::login($user);
    
            switch ($user->rol) {
                case 'Administrador':
                    return redirect()->route('homeAdmin');
                case 'Vendedor':
                    return redirect()->route('homeVendedor');
                case 'Cliente':
                    return redirect()->route('homeCliente');
                default:
                    return redirect()->route('auth.login')->withErrors([
                        'rol' => 'Rol no válido.',
                    ]);
            }
        }
    
        return back()->withErrors([
            'correo' => 'Las credenciales proporcionadas no son válidas.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout(); 
        $request->session()->invalidate(); 
        $request->session()->regenerateToken(); 
        return redirect()->route('login');
    }

    public function register(Request $request) {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellidoP' => 'required|string|max:255',
            'apellidoM' => 'required|string|max:255',
            'correo' => 'required|email|unique:users,correo',
            'contraseña' => 'required|min:8|confirmed',
        ]);

        $user = User::create([
            'nombre' => $request->nombre,
            'apellidoP' => $request->apellidoP,
            'apellidoM' => $request->apellidoM,
            'correo' => $request->correo,
            'password' => Hash::make($request->contraseña), 
            'rol' => 'Cliente', 
        ]);

        Auth::login($user);

        $redirectTo = ($user->rol === 'Administrador') ? 'homeAdmin' : 'homeCliente';
        return redirect()->route($redirectTo);
    }
}