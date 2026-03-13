<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BolsaTrabajo extends Model
{
    use HasFactory;

    protected $table = 'bolsa_trabajo';

    protected $fillable = [
        'user_id',
        'titulo',
        'empresa',
        'ubicacion',
        'telefono_contacto',
        'email_contacto',
        'descripcion',
        'requisitos',
        'fecha_publicacion',
        'fecha_vencimiento',
        'estado',
        'visible',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
        'fecha_vencimiento' => 'date',
        'visible' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ingenierias()
    {
        return $this->belongsToMany(
            Ingenieria::class,
            'bolsa_trabajo_ingenierias'
        );
    }
}