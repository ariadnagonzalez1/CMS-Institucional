<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ConfiguracionSitio extends Model
{
    use HasFactory;

    protected $table = 'configuracion_sitio';

    protected $fillable = [
        'dominio',
        'email_principal',
        'titulo_sitio',
        'descripcion_sitio',
        'palabras_clave',
        'telefono',
        'celular',
        'whatsapp',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'youtube_url',
        'google_maps_url',
        'path_directorios',
        'directorio_raiz',
        'email_envio',
        'limite_peso_imagen_kb',
        'limite_peso_multimedia_kb',
        'limite_peso_archivo_kb',
        'ancho_imagen_grande_px',
        'ancho_imagen_mediana_px',
        'ancho_imagen_chica_px',
        'ancho_imagen_minima_px',
        'checked_multimedia',
        'checked_archivos',
        'caja_info_footer',
        'activo',
    ];

    protected $casts = [
        'checked_multimedia' => 'boolean',
        'checked_archivos' => 'boolean',
        'activo' => 'boolean',
    ];
}