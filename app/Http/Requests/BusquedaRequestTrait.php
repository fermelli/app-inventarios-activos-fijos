<?php

namespace App\Http\Requests;

trait BusquedaRequestTrait
{
    protected function busquedaRules(): array
    {
        return [
            'busqueda' => ['string'],
        ];
    }
}
