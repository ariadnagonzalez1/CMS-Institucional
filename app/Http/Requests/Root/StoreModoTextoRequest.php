<?php

namespace App\Http\Requests\Root;

use Illuminate\Foundation\Http\FormRequest;

class StoreModoTextoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'nombre'         => ['required', 'string', 'max:100'],
            'descripcion'    => ['nullable', 'string', 'max:255'],
            'cantidad_cajas' => ['nullable', 'integer', 'min:1', 'max:255'],
            'estado'         => ['boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'estado' => $this->boolean('estado'),
        ]);
    }
}