<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModoTexto extends Model
{
    use HasFactory;

    protected $table = 'modos_texto';

    protected $fillable = [
        'nombre',
        'descripcion',
        'cantidad_cajas',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function seccionesNoticias()
    {
        return $this->hasMany(SeccionNoticia::class);
    }

    public function noticias()
    {
        return $this->hasMany(Noticia::class);
    }
}