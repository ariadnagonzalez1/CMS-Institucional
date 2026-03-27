<?php
// app/Http/Requests/Admin/DescargableRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DescargableRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'seccion_descargable_id' => 'required|exists:secciones_descargables,id',
            'tema' => 'required|string|max:255',
            'comentario' => 'nullable|string',
            'fecha_publicacion' => 'required|date',
            'estado' => 'sometimes|boolean',
        ];

        // Si es creación o se sube un nuevo archivo, validar el archivo
        if ($this->isMethod('POST') || $this->hasFile('archivo')) {
            $rules['archivo'] = 'required|file|mimes:pdf,doc,docx,xls,xlsx,zip|max:10240'; // 10MB max
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'seccion_descargable_id.required' => 'Debe seleccionar una sección.',
            'seccion_descargable_id.exists' => 'La sección seleccionada no es válida.',
            'tema.required' => 'El tema es obligatorio.',
            'fecha_publicacion.required' => 'La fecha de publicación es obligatoria.',
            'archivo.required' => 'Debe seleccionar un archivo.',
            'archivo.mimes' => 'El archivo debe ser de tipo: PDF, Word, Excel o ZIP.',
            'archivo.max' => 'El archivo no debe pesar más de 10MB.',
        ];
    }
}