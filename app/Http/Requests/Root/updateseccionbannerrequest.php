<?php

namespace App\Http\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeccionBannerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:150'],
            'ancho'            => ['required', 'integer', 'min:1'],
            'alto'             => ['nullable', 'integer', 'min:1'],
            'cantidad_limite'  => ['nullable', 'integer', 'min:1'],
            'comentario'       => ['nullable', 'string', 'max:255'],
            'imagen_ayuda'     => ['nullable', 'image', 'max:2048'],
            'visible_en_sitio' => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'visible_en_sitio' => $this->boolean('visible_en_sitio'),
        ]);
    }
}