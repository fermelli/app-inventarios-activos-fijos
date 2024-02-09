<?php

namespace App\Http\Requests;

use App\Models\Transaccion;
use Illuminate\Foundation\Http\FormRequest;

class CrearEntradaArticulosRequest extends FormRequest
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
            'institucion_id' => ['required', 'integer', 'exists:instituciones,id'],
            'fecha' => ['required', 'date'],
            'comprobante' => ['required', 'string', 'in:' . implode(',', Transaccion::COMPROBANTES)],
            'numero_comprobante' => ['required', 'string', 'max:100'],
            'observacion' => ['nullable', 'string', 'max:255'],
            'detalles_transacciones' => ['required', 'array'],
            'detalles_transacciones.*.articulo_id' => ['required', 'integer', 'exists:articulos,id'],
            'detalles_transacciones.*.cantidad' => ['required', 'numeric', 'min:0.01'],
            'detalles_transacciones.*.articulo_lote.lote' => ['nullable', 'string', 'max:100'],
            'detalles_transacciones.*.articulo_lote.fecha_vencimiento' => ['nullable', 'date'],
        ];
    }
}
