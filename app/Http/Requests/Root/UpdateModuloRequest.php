<?php

namespace App\Http\Requests\Root;

use App\Enums\ModuloTipo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateModuloRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'    => ['required', 'string', 'max:120'],
            'tipo'      => ['nullable', Rule::enum(ModuloTipo::class)],
            'path_home' => ['nullable', 'string', 'max:255'],
            'icono'     => ['nullable', 'string', 'max:100'],
            'orden'     => ['nullable', 'integer', 'min:0'],
            'estado'    => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo.enum' => 'El tipo seleccionado no es válido.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'estado' => $this->boolean('estado'),
            'tipo'   => $this->input('tipo') ?: null,
            'orden'  => $this->input('orden', 0),
        ]);
    }
}