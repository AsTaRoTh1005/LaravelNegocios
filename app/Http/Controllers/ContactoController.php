<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsultaMail;

class ContactoController extends Controller
{
    public function enviarConsulta(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email',
            'mensaje' => 'required|string',
        ]);

        $data = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'mensaje' => $request->mensaje,
        ];

        Mail::to('22610201@utgz.edu.mx')->send(new ConsultaMail($data));

        return redirect()->back()->with('success', 'Tu mensaje ha sido enviado correctamente.');
    }
}
