<?php

namespace App\Http\Requests\Traits;

trait BusquedaRequestTrait
{
    protected function busquedaRules(): array
    {
        return [
            'busqueda' => ['string'],
        ];
    }
}
