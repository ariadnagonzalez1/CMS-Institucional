<?php
// app/Http/Requests/Multimedia/UpdateMultimediaRequest.php

namespace App\Http\Requests\Multimedia;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMultimediaRequest extends FormRequest
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
            'archivo' => 'nullable|file|mimes:mp3|max:10240',
            'estado' => 'boolean',
        ];
    }
}