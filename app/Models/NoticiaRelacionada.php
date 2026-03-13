<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NoticiaRelacionada extends Model
{
    use HasFactory;

    protected $table = 'noticias_relacionadas';

    protected $fillable = [
        'noticia_id',
        'noticia_relacionada_id',
    ];

    public function noticia()
    {
        return $this->belongsTo(Noticia::class, 'noticia_id');
    }

    public function relacionada()
    {
        return $this->belongsTo(Noticia::class, 'noticia_relacionada_id');
    }
}