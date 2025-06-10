<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GitHubController extends Controller
{
    public function redirectToGitHub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGitHubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::where('github_id', $githubUser->getId())->first();

        if (!$user) {
            $user = User::create([
                'nombre'     => $githubUser->getName() ?? $githubUser->getNickname(),
                'apellidoP'  => 'Sin especificar',
                'apellidoM'  => 'Sin especificar',
                'correo'     => $githubUser->getEmail(),
                'github_id' => $githubUser->getId(),
                'avatar'    => $githubUser->getAvatar(),
                'password'   => bcrypt(uniqid()), // Contraseña aleatoria
                'rol'        => 'Cliente',
            ]);
        }

        Auth::login($user, true);

        return $this->redirectBasedOnRole($user->rol);
    }

    protected function redirectBasedOnRole($rol)
    {
        switch ($rol) {
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
}