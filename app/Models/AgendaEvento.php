<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AgendaEvento extends Model
{
    use HasFactory;

    protected $table = 'agenda_eventos';

    protected $fillable = [
        'seccion_agenda_id',
        'user_id',
        'titulo',
        'fecha_evento',
        'hora_evento',
        'lugar',
        'descripcion',
        'tipo_fijacion',
        'tipo_ventana',
        'estado',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
        'hora_evento' => 'datetime:H:i',
        'estado' => 'boolean',
    ];

    public function seccion()
    {
        return $this->belongsTo(SeccionAgenda::class, 'seccion_agenda_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}