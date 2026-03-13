<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ingenieria extends Model
{
    use HasFactory;

    protected $table = 'ingenierias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activa',
    ];

    protected $casts = [
        'activa' => 'boolean',
    ];

    public function contactos()
    {
        return $this->hasMany(ContactoIngenieria::class);
    }

    public function bolsaTrabajoIngenierias()
    {
        return $this->hasMany(BolsaTrabajoIngenieria::class);
    }

    public function solicitudesEmpresasIngenierias()
    {
        return $this->hasMany(SolicitudEmpresaIngenieria::class);
    }
}