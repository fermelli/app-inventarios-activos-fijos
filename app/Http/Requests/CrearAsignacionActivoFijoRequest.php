<?php

namespace App\Http\Requests;

use App\Models\Articulo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrearAsignacionActivoFijoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'activo_fijo_id' => [
                'required',
                'integer',
                Rule::exists('articulos', 'id')->where('tipo', Articulo::TIPO_ACTIVO_FIJO),
            ],
            'asignado_a_id' => ['required', 'integer', 'exists:usuarios,id'],
            'ubicacion_id' => ['required', 'integer', 'exists:ubicaciones,id'],
            'observacion_asignacion' => ['required', 'string', 'max:1000'],
            'fecha_asignacion' => ['required', 'date'],
            'fecha_devolucion' => ['nullable', 'date'],
        ];
    }
}
