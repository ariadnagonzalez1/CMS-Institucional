<?php
// app/Http/Requests/Noticias/UpdateNoticiaRequest.php

namespace App\Http\Requests\Noticias;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNoticiaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'modo_texto_id' => 'required|exists:modos_texto,id',
            'seccion_noticia_id' => 'required|exists:secciones_noticias,id',
            'fecha_publicacion' => 'required|date',
            'volanta' => 'nullable|string|max:255',
            'titulo' => 'required|string|max:255',
            'bajada' => 'nullable|string',
            'cuerpo' => 'required|string',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagenes_titulo.*' => 'nullable|string|max:255',
            'imagenes_descripcion.*' => 'nullable|string|max:255',
            'imagenes_alt.*' => 'nullable|string|max:255',
        ];
    }
}