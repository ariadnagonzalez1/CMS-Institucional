<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaRedaccion extends Model
{
    use HasFactory;

    protected $table = 'salas_redaccion';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}