<?php
// app/Http/Requests/Noticias/StoreNoticiaRequest.php

namespace App\Http\Requests\Noticias;

use Illuminate\Foundation\Http\FormRequest;

class StoreNoticiaRequest extends FormRequest
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

    public function messages()
    {
        return [
            'modo_texto_id.required' => 'El modo es obligatorio',
            'seccion_noticia_id.required' => 'La sección es obligatoria',
            'titulo.required' => 'El título es obligatorio',
            'cuerpo.required' => 'El contenido es obligatorio',
            'imagenes.*.image' => 'El archivo debe ser una imagen',
            'imagenes.*.max' => 'La imagen no debe pesar más de 2MB',
        ];
    }
}