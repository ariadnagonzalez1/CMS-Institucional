<?php

namespace App\Enums;

/**
 * ModuloTipo
 * app/Enums/ModuloTipo.php
 *
 * Fuente única de verdad para los tipos válidos del campo `tipo` en la tabla `modulos`.
 *
 * ── Cómo funciona ────────────────────────────────────────────────
 *   - El select del modal itera: @foreach(\App\Enums\ModuloTipo::cases() as $t)
 *   - El FormRequest valida con: Rule::enum(ModuloTipo::class)
 *   - Para agregar un tipo nuevo: solo añadís un case acá.
 */
enum ModuloTipo: string
{
    case Noticias     = 'noticias';
    case Banners      = 'banners';
    case Multimedia   = 'multimedia';
    case Descargables = 'descargables';
    case Agenda       = 'agenda';
    case Galeria      = 'galeria';
    case Custom       = 'custom';

    public function label(): string
    {
        return match ($this) {
            self::Noticias     => 'Noticias',
            self::Banners      => 'Banners',
            self::Multimedia   => 'Multimedia',
            self::Descargables => 'Descargables',
            self::Agenda       => 'Agenda',
            self::Galeria      => 'Galería',
            self::Custom       => 'Custom',
        };
    }
}