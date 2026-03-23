<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeccionTexto extends Model
{
    // La tabla real en la BD es secciones_noticias
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
        'orden'            => 'integer',
    ];

    // Relación con ModoTexto
    public function modoTexto(): BelongsTo
    {
        return $this->belongsTo(ModoTexto::class, 'modo_texto_id');
    }
}