<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoticiaImagen extends Model
{
    use HasFactory;

    protected $table = 'noticias_imagenes';

    protected $fillable = [
        'noticia_id',
        'archivo',
        'titulo',
        'descripcion',
        'alt_text',
        'orden',
        'es_principal',
        'ancho',
        'alto',
        'recortada',
    ];

    protected $casts = [
        'es_principal' => 'boolean',
        'recortada' => 'boolean',
    ];

    public function noticia()
    {
        return $this->belongsTo(Noticia::class);
    }
}