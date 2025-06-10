<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class DiscordController extends Controller
{
    public function redirectToDiscord()
    {
        return Socialite::driver('discord')->redirect();
    }

    public function handleDiscordCallback()
    {
        $discordUser = Socialite::driver('discord')->user();

        $user = User::where('discord_id', $discordUser->getId())->first();

        if (!$user) {
            $user = User::create([
                'nombre'     => $discordUser->getName(),
                'correo'     => $discordUser->getEmail(),
                'discord_id' => $discordUser->getId(),
                'avatar'     => $discordUser->getAvatar(),
                'password'   => bcrypt(uniqid()),
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