<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Noticia extends Model
{
    use HasFactory;

    protected $table = 'noticias';

    protected $fillable = [
        'modo_texto_id',
        'seccion_noticia_id',
        'user_id',
        'slug',
        'fecha_publicacion',
        'volanta',
        'titulo',
        'bajada',
        'cuerpo',
        'visitas',
        'comentarios_count',
        'visible',
        'activa',
        'nivel_destacado',
        'es_destacado_portada',
        'es_superdestacado_portada',
        'permite_comentarios',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'visible' => 'boolean',
        'activa' => 'boolean',
        'es_destacado_portada' => 'boolean',
        'es_superdestacado_portada' => 'boolean',
        'permite_comentarios' => 'boolean',
    ];

    public function modoTexto()
    {
        return $this->belongsTo(ModoTexto::class);
    }

    public function seccion()
    {
        return $this->belongsTo(SeccionNoticia::class, 'seccion_noticia_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function imagenes()
    {
        return $this->hasMany(NoticiaImagen::class);
    }

    public function noticiasRelacionadas()
    {
        return $this->hasMany(NoticiaRelacionada::class);
    }
}