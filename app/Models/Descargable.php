<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Descargable extends Model
{
    use HasFactory;

    protected $table = 'descargables';

    protected $fillable = [
        'seccion_descargable_id',
        'user_id',
        'fecha_publicacion',
        'tema',
        'comentario',
        'archivo',
        'nombre_original_archivo',
        'tipo_archivo',
        'tamano_archivo_kb',
        'total_descargas',
        'estado',
        'visible',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'estado' => 'boolean',
        'visible' => 'boolean',
    ];

    public function seccion()
    {
        return $this->belongsTo(SeccionDescargable::class, 'seccion_descargable_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}