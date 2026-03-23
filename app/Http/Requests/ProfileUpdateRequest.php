<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'         => ['required', 'string', 'max:255'],
            'apellido'     => ['nullable', 'string', 'max:100'],
            'dni'          => ['nullable', 'string', 'max:20'],
            'username'     => ['nullable', 'string', 'max:50', Rule::unique(User::class)->ignore($this->user()->id)],
            'email'        => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'telefono_fijo'=> ['nullable', 'string', 'max:30'],
            'celular'      => ['nullable', 'string', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'Ese nombre de usuario ya está en uso.',
            'email.unique'    => 'Ese email ya está registrado.',
        ];
    }
}