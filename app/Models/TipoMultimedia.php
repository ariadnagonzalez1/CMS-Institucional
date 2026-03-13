<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoMultimedia extends Model
{
    use HasFactory;

    protected $table = 'tipos_multimedia';

    protected $fillable = [
        'nombre',
        'descripcion',
        'extension',
        'es_embed',
        'activo',
    ];

    protected $casts = [
        'es_embed' => 'boolean',
        'activo' => 'boolean',
    ];

    public function multimedia()
    {
        return $this->hasMany(Multimedia::class, 'tipo_multimedia_id');
    }
}