<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Privilegio extends Model
{
    use HasFactory;

    protected $table = 'privilegios';

    protected $fillable = [
        'nombre',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function usuarioPrivilegios()
    {
        return $this->hasMany(UsuarioPrivilegio::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'usuarios_privilegios');
    }
}