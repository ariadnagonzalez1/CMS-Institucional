<?php
// app/Http/Requests/Admin/AdminRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'nombre' => 'required|string|max:255',
            'apellido' => 'nullable|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'dni' => 'required|string|max:20|unique:users,dni',
            'celular' => 'nullable|string|max:30',
            'telefono_fijo' => 'nullable|string|max:30',
            'sala_redaccion_id' => 'required|exists:salas_redaccion,id',
            'modo_grupo_id' => 'required|exists:modos_grupo,id',
            'privilegios' => 'required|array|min:1',
            'privilegios.*' => 'exists:privilegios,id',
            'activo' => 'sometimes|boolean',
        ];

        // En edición, ignorar la unicidad del propio registro
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $userId = $this->route('admin')->id ?? $this->user_id;
            $rules['email'] = ['required', 'email', 'max:255', Rule::unique('users')->ignore($userId)];
            $rules['dni'] = ['required', 'string', 'max:20', Rule::unique('users')->ignore($userId)];
        }

        // Si es creación, la clave es requerida
        if ($this->isMethod('POST')) {
            $rules['password'] = 'required|string|min:6|max:8';
        } else {
            $rules['password'] = 'nullable|string|min:6|max:8';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'Este DNI ya está registrado.',
            'email.required' => 'El email es obligatorio.',
            'email.unique' => 'Este email ya está registrado.',
            'password.required' => 'La clave es obligatoria.',
            'password.min' => 'La clave debe tener al menos 6 caracteres.',
            'password.max' => 'La clave no puede exceder los 8 caracteres.',
            'privilegios.required' => 'Debe seleccionar al menos un privilegio.',
            'sala_redaccion_id.required' => 'La sala de redacción es obligatoria.',
            'modo_grupo_id.required' => 'El modo de grupo es obligatorio.',
        ];
    }
}