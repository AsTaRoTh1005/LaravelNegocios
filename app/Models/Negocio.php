<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Negocio extends Model
{
    use HasFactory;

    protected $primaryKey = 'negocio_id';
    protected $table = 'negocios';

    protected $fillable = [
        'id_usuario',
        'nombre',
        'descripcion',
        'direccion',
        'telefono',
        'imagen'
    ];

    /**
     * Relación con el usuario dueño del negocio
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Relación con los productos del negocio
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'negocio_id', 'negocio_id');
    }

    /**
     * Accesor para la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/'.$this->imagen) : null;
    }
}