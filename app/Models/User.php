<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Two\User as GoogleUser;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Nombre de la clave primaria.
     *
     * @var string
     */
    protected $primaryKey = 'id_usuario';

    /**
     * Indica si la clave primaria es autoincremental.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indica si el modelo debe tener marcas de tiempo.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Atributos que son asignables en masa.
     *
     * @var array
     */
    protected $fillable = [
        'nombre',
        'apellidoP',
        'apellidoM',
        'correo',
        'password',
        'google_id',
        'github_id',
        'avatar',
        'rol',
    ];

    /**
     * Atributos que deben estar ocultos para la serialización.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Atributos que deben ser convertidos a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Obtiene el nombre del campo de contraseña.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password; // Se cambia 'contraseña' por 'password'
    }

    /**
     * Crea o actualiza un usuario desde Google.
     *
     * @param \Laravel\Socialite\Two\User $googleUser
     * @return \App\Models\User
     */
    public static function createOrUpdateFromGoogle(GoogleUser $googleUser)
    {
        $user = self::where('correo', $googleUser->getEmail())->first();

        if (!$user) {
            // Separar nombre completo en nombre y apellidos
            $fullName = explode(' ', $googleUser->getName(), 3);
            $nombre = $fullName[0] ?? '';
            $apellidoP = $fullName[1] ?? '';
            $apellidoM = $fullName[2] ?? '';

            // Crear usuario
            $user = self::create([
                'nombre'     => $nombre,
                'apellidoP'  => $apellidoP,
                'apellidoM'  => $apellidoM,
                'correo'     => $googleUser->getEmail(),
                'google_id'  => $googleUser->getId(),
                'avatar'     => $googleUser->getAvatar(),
                'password'   => Hash::make(uniqid()), 
                'rol'        => 'Cliente', // Rol por defecto
            ]);
        }

        return $user;
    }
    // app/Models/User.php
// app/Models/User.php
public function negocio()
{
    return $this->hasOne(Negocio::class, 'id_usuario');
}
}
