<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'sala_redaccion_id',
        'modo_grupo_id',
        'dni',
        'username',
        'name',
        'apellido',
        'email',
        'password',
        'telefono_fijo',
        'celular',
        'avatar',
        'activo',
        'ultimo_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
            'ultimo_login' => 'datetime',
        ];
    }

    public function salaRedaccion()
    {
        return $this->belongsTo(SalaRedaccion::class);
    }

    public function modoGrupo()
    {
        return $this->belongsTo(ModoGrupo::class);
    }

    public function usuarioPrivilegios()
    {
        return $this->hasMany(UsuarioPrivilegio::class);
    }

    public function privilegios()
    {
        return $this->belongsToMany(Privilegio::class, 'usuarios_privilegios');
    }

    public function noticias()
    {
        return $this->hasMany(Noticia::class);
    }

    public function banners()
    {
        return $this->hasMany(Banner::class);
    }

    public function multimedia()
    {
        return $this->hasMany(Multimedia::class);
    }

    public function albumesFotos()
    {
        return $this->hasMany(AlbumFoto::class);
    }

    public function agendaEventos()
    {
        return $this->hasMany(AgendaEvento::class);
    }

    public function descargables()
    {
        return $this->hasMany(Descargable::class);
    }

    public function bolsasTrabajo()
    {
        return $this->hasMany(BolsaTrabajo::class);
    }
}