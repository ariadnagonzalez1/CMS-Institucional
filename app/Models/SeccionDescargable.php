<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeccionDescargable extends Model
{
    use HasFactory;

    protected $table = 'secciones_descargables';

    protected $fillable = [
        'nombre',
        'descripcion',
        'visible_en_sitio',
        'orden',
    ];

    protected $casts = [
        'visible_en_sitio' => 'boolean',
    ];

    public function descargables()
    {
        return $this->hasMany(Descargable::class, 'seccion_descargable_id');
    }
}