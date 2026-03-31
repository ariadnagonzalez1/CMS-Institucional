<?php
// app/Http/Requests/Multimedia/StoreMultimediaRequest.php

namespace App\Http\Requests\Multimedia;

use Illuminate\Foundation\Http\FormRequest;

class StoreMultimediaRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'seccion_multimedia_id' => 'required|exists:secciones_multimedia,id',
            'tipo_multimedia_id' => 'required|exists:tipos_multimedia,id',
            'fecha_publicacion' => 'required|date',
            'tema' => 'required|string|max:255',
            'codigo_embed' => 'required_if:tipo_multimedia_id,1|nullable|string',
            'archivo' => 'required_if:tipo_multimedia_id,2|nullable|file|mimes:mp3|max:10240',
            'estado' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'seccion_multimedia_id.required' => 'La sección es obligatoria',
            'tipo_multimedia_id.required' => 'El tipo de multimedia es obligatorio',
            'tema.required' => 'El tema es obligatorio',
            'codigo_embed.required_if' => 'El código embed es obligatorio para YouTube/Ivoox',
            'archivo.required_if' => 'El archivo MP3 es obligatorio',
            'archivo.mimes' => 'El archivo debe ser MP3',
            'archivo.max' => 'El archivo no debe pesar más de 10MB',
        ];
    }
}