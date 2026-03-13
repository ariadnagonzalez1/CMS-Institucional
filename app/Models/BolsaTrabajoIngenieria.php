<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BolsaTrabajoIngenieria extends Model
{
    use HasFactory;

    protected $table = 'bolsa_trabajo_ingenierias';

    protected $fillable = [
        'bolsa_trabajo_id',
        'ingenieria_id',
    ];

    public function bolsaTrabajo()
    {
        return $this->belongsTo(BolsaTrabajo::class);
    }

    public function ingenieria()
    {
        return $this->belongsTo(Ingenieria::class);
    }
}