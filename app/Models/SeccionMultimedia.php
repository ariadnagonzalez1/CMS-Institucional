<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeccionMultimedia extends Model
{
    use HasFactory;

    protected $table = 'secciones_multimedia';

    protected $fillable = [
        'nombre',
        'descripcion',
        'visible_en_sitio',
    ];

    protected $casts = [
        'visible_en_sitio' => 'boolean',
    ];

    public function multimedia()
    {
        return $this->hasMany(Multimedia::class, 'seccion_multimedia_id');
    }
}