<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudEmpresa extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_empresas';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'empresa',
        'asunto',
        'ubicacion',
        'mensaje',
        'estado',
    ];

    public function ingenierias()
    {
        return $this->belongsToMany(
            Ingenieria::class,
            'solicitudes_empresas_ingenierias',
            'solicitud_empresa_id',
            'ingenieria_id'
        );
    }
}