<?php
// app/Http/Requests/Admin/AgendaEventoRequest.php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AgendaEventoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'seccion_agenda_id' => 'required|exists:secciones_agenda,id',
            'titulo' => 'required|string|max:255',
            'fecha_evento' => 'required|date',
            'hora_evento' => 'nullable|date_format:H:i',
            'lugar' => 'nullable|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_fijacion' => 'required|string|in:ninguno,destacado,superdestacado',
            'tipo_ventana' => 'nullable|string|max:30',
            'estado' => 'sometimes|boolean',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'seccion_agenda_id.required' => 'Debe seleccionar una sección de agenda.',
            'seccion_agenda_id.exists' => 'La sección seleccionada no es válida.',
            'titulo.required' => 'El título es obligatorio.',
            'fecha_evento.required' => 'La fecha del evento es obligatoria.',
            'fecha_evento.date' => 'La fecha no es válida.',
            'tipo_fijacion.required' => 'Debe seleccionar el tipo de fijación.',
            'tipo_fijacion.in' => 'El tipo de fijación no es válido.',
        ];
    }
}