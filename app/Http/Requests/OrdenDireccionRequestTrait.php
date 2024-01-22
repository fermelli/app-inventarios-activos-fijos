<?php

namespace App\Http\Requests;

trait OrdenDireccionRequestTrait
{
    protected function ordenDireccionRules(): array
    {
        return [
            'orden_direccion' => ['required', 'in:asc,desc'],
        ];
    }

    protected function ordenDireccionPrepareForValidation(): void
    {
        $this->merge([
            'orden_direccion' => $this->orden_direccion ?? 'desc',
        ]);
    }
}
