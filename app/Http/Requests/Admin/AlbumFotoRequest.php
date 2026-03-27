<?php
// app/Http/Requests/Admin/AlbumFotoRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AlbumFotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'visible' => 'sometimes|boolean',
            'estado' => 'sometimes|boolean',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del álbum es obligatorio.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
        ];
    }
}