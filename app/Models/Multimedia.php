<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Multimedia extends Model
{
    use HasFactory;

    protected $table = 'multimedia';

    protected $fillable = [
        'seccion_multimedia_id',
        'tipo_multimedia_id',
        'user_id',
        'fecha_publicacion',
        'tema',
        'codigo_embed',
        'archivo',
        'url_externa',
        'estado',
        'orden',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'estado' => 'boolean',
    ];

    public function seccion()
    {
        return $this->belongsTo(SeccionMultimedia::class, 'seccion_multimedia_id');
    }

    public function tipo()
    {
        return $this->belongsTo(TipoMultimedia::class, 'tipo_multimedia_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}