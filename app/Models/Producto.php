<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    protected $table = 'productos';

    protected $fillable = [
        'negocio_id',
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen'
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    /**
     * Relación con el negocio al que pertenece el producto
     */
    public function negocio()
    {
        return $this->belongsTo(Negocio::class, 'negocio_id', 'negocio_id');
    }

    /**
     * Accesor para la URL completa de la imagen
     */
    public function getImagenUrlAttribute()
    {
        return $this->imagen ? asset('storage/'.$this->imagen) : null;
    }
}