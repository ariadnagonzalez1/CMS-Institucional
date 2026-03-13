<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlbumFotoItem extends Model
{
    use HasFactory;

    protected $table = 'album_fotos';

    protected $fillable = [
        'album_id',
        'archivo',
        'nombre_archivo',
        'epigrafe',
        'es_foto_epigrafe',
        'es_portada',
        'orden',
        'ancho',
        'alto',
        'recortada',
    ];

    protected $casts = [
        'es_foto_epigrafe' => 'boolean',
        'es_portada' => 'boolean',
        'recortada' => 'boolean',
    ];

    public function album()
    {
        return $this->belongsTo(AlbumFoto::class, 'album_id');
    }
}