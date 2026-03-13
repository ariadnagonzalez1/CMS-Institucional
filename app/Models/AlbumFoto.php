<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlbumFoto extends Model
{
    use HasFactory;

    protected $table = 'albumes_fotos';

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'visible',
        'estado',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'estado' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function fotos()
    {
        return $this->hasMany(AlbumFotoItem::class, 'album_id');
    }
}