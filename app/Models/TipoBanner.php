<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoBanner extends Model
{
    use HasFactory;

    protected $table = 'tipos_banners'; // ✅ Nombre correcto de la tabla

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function banners()
    {
        return $this->hasMany(Banner::class, 'tipo_banner_id');
    }
}