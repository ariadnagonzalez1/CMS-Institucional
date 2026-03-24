<?php

namespace App\Http\Requests\Banners;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBannerRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'seccion_banner_id' => ['required', 'integer', 'exists:secciones_banners,id'],
            'tipo_banner_id'    => ['nullable', 'integer', 'exists:tipos_banners,id'],
            'titulo_epigrafe'   => ['nullable', 'string', 'max:255'],
            'comentario'        => ['nullable', 'string'],
            'ruta_imagen'       => ['required', 'string', 'max:255'],
            'borde_px'          => ['nullable', 'integer', 'min:0'],
            'color_borde'       => ['nullable', 'string', 'max:20'],
            'alineacion'        => ['nullable', 'string', 'max:20'],
            'ajuste_ancho'      => ['nullable', 'string', 'max:50'],
            'tipo_link'         => ['nullable', 'string', 'max:30'],
            'url_destino'       => ['nullable', 'url', 'max:500'],
            'tipo_ventana'      => ['nullable', 'string', 'max:30'],
            'estado'            => ['nullable', 'string', 'in:activo,inactivo'],
            'orden'             => ['nullable', 'integer', 'min:0'],
            'fecha_inicio'      => ['nullable', 'date'],
            'fecha_fin'         => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'seccion_banner_id.required' => 'La sección es obligatoria.',
            'seccion_banner_id.exists'   => 'La sección seleccionada no existe.',
            'ruta_imagen.required'       => 'La imagen es obligatoria.',
            'url_destino.url'            => 'La URL de destino no tiene un formato válido.',
            'fecha_fin.after_or_equal'   => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tipo_banner_id' => $this->input('tipo_banner_id') ?: null,
            'orden'          => $this->input('orden', 0),
            'estado'         => $this->input('estado', 'activo'),
        ]);
    }
}