<?php

namespace App\Http\Requests\Traits;

trait CrearSolicitudArticuloRequestTrait
{
    protected function crearSolicitudArticuloRules(): array
    {
        return [
            'observacion' => ['nullable', 'string', 'max:255'],
            'detalles_transacciones' => ['required', 'array'],
            'detalles_transacciones.*.articulo_id' => ['required', 'integer', 'exists:articulos,id'],
            'detalles_transacciones.*.cantidad' => ['required', 'numeric', 'min:0.01'],
        ];
    }
}
