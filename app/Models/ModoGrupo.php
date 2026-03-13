<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ModoGrupo extends Model
{
    use HasFactory;

    protected $table = 'modos_grupo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'puede_ver',
        'puede_compartir',
        'activo',
    ];

    protected $casts = [
        'puede_ver' => 'boolean',
        'puede_compartir' => 'boolean',
        'activo' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}