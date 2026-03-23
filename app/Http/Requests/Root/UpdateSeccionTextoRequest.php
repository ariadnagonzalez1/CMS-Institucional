<?php

namespace App\Http\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSeccionTextoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'           => ['required', 'string', 'max:150'],
            'modo_texto_id'    => ['required', 'integer', 'exists:modos_texto,id'],
            'color_fondo'      => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_texto'      => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_borde'      => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'orden'            => ['nullable', 'integer', 'min:0'],
            'visible_en_sitio' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'modo_texto_id.exists' => 'El modo de texto seleccionado no existe.',
            'color_fondo.regex'    => 'Color de fondo inválido. Usá formato #rrggbb.',
            'color_texto.regex'    => 'Color de texto inválido. Usá formato #rrggbb.',
            'color_borde.regex'    => 'Color de borde inválido. Usá formato #rrggbb.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'visible_en_sitio' => $this->boolean('visible_en_sitio'),
            'orden'            => $this->input('orden', 0),
            'color_fondo'      => $this->input('color_fondo') ?: null,
            'color_texto'      => $this->input('color_texto') ?: null,
            'color_borde'      => $this->input('color_borde') ?: null,
        ]);
    }
}