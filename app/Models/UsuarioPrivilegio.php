<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UsuarioPrivilegio extends Model
{
    use HasFactory;

    protected $table = 'usuarios_privilegios';

    protected $fillable = [
        'user_id',
        'privilegio_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function privilegio()
    {
        return $this->belongsTo(Privilegio::class);
    }
}