<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContactoIngenieria extends Model
{
    use HasFactory;

    protected $table = 'contactos_ingenierias';

    protected $fillable = [
        'ingenieria_id',
        'nombre_contacto',
        'email',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function ingenieria()
    {
        return $this->belongsTo(Ingenieria::class);
    }
}