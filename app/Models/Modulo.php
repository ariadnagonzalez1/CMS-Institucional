<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Modulo extends Model
{
    use HasFactory;

    protected $table = 'modulos';

    protected $fillable = [
        'nombre',
        'tipo',
        'path_home',
        'icono',
        'orden',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}