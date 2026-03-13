<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeccionNoticia extends Model
{
    use HasFactory;

    protected $table = 'secciones_noticias';

    protected $fillable = [
        'modo_texto_id',
        'nombre',
        'color_fondo',
        'color_texto',
        'color_borde',
        'visible_en_sitio',
        'orden',
    ];

    protected $casts = [
        'visible_en_sitio' => 'boolean',
    ];

    public function modoTexto()
    {
        return $this->belongsTo(ModoTexto::class);
    }

    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'seccion_noticia_id');
    }
}