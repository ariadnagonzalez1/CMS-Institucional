<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';

    protected $fillable = [
        'seccion_banner_id',
        'tipo_banner_id',
        'user_id',
        'titulo_epigrafe',
        'comentario',
        'ruta_imagen',
        'borde_px',
        'color_borde',
        'alineacion',
        'ajuste_ancho',
        'tipo_link',
        'url_destino',
        'tipo_ventana',
        'estado',
        'orden',
        'fecha_inicio',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    // Relación con sección (tabla: secciones_banners)
    public function seccion()
    {
        return $this->belongsTo(SeccionBanner::class, 'seccion_banner_id');
    }

    // Relación con tipo (tabla: tipos_banners)
    public function tipo()
    {
        return $this->belongsTo(TipoBanner::class, 'tipo_banner_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}