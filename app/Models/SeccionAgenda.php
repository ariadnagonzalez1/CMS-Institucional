<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SeccionAgenda extends Model
{
    use HasFactory;

    protected $table = 'secciones_agenda';

    protected $fillable = [
        'nombre',
        'descripcion',
        'visible_en_sitio',
    ];

    protected $casts = [
        'visible_en_sitio' => 'boolean',
    ];

    public function eventos()
    {
        return $this->hasMany(AgendaEvento::class, 'seccion_agenda_id');
    }
}