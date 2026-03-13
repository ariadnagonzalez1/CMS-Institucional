<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeccionBanner extends Model
{
    use HasFactory;

    protected $table = 'secciones_banners';

    protected $fillable = [
        'nombre',
        'ancho',
        'alto',
        'cantidad_limite',
        'comentario',
        'imagen_ayuda',
        'visible_en_sitio',
    ];

    protected $casts = [
        'visible_en_sitio' => 'boolean',
    ];

    public function banners()
    {
        return $this->hasMany(Banner::class, 'seccion_banner_id');
    }
}