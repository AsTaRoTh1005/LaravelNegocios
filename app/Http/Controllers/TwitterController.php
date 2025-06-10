<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class TwitterController extends Controller
{
    public function redirectToTwitter()
    {
        return Socialite::driver('twitter')->redirect();
    }

    public function handleTwitterCallback()
    {
        $twitterUser = Socialite::driver('twitter')->user();

        $user = User::where('twitter_id', $twitterUser->getId())->first();

        if (!$user) {
            $user = User::create([
                'nombre'     => $twitterUser->getName(),
                'correo'     => $twitterUser->getEmail() ?? $twitterUser->getId() . '@twitter.com', // Twitter no siempre devuelve email
                'twitter_id' => $twitterUser->getId(),
                'avatar'    => $twitterUser->getAvatar(),
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