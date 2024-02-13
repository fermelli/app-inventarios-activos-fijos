<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CrearSolicitudArticuloRequest extends FormRequest
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
            'observacion' => ['nullable', 'string', 'max:255'],
            'detalles_transacciones' => ['required', 'array'],
            'detalles_transacciones.*.articulo_id' => ['required', 'integer', 'exists:articulos,id'],
            'detalles_transacciones.*.cantidad' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
