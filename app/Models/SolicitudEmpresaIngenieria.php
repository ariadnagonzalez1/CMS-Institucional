<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SolicitudEmpresaIngenieria extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_empresas_ingenierias';

    protected $fillable = [
        'solicitud_empresa_id',
        'ingenieria_id',
    ];

    public function solicitudEmpresa()
    {
        return $this->belongsTo(SolicitudEmpresa::class, 'solicitud_empresa_id');
    }

    public function ingenieria()
    {
        return $this->belongsTo(Ingenieria::class);
    }
}