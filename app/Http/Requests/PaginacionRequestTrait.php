<?php

namespace App\Http\Requests;

trait PaginacionRequestTrait
{
    protected function paginacionRules(): array
    {
        return [
            'pagina' => ['required', 'integer', 'min:1'],
            'por_pagina' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function paginacionPrepareForValidation(): void
    {
        $this->merge([
            'pagina' => !array_key_exists('pagina', $this->all()) ?
            1 :
            $this->pagina,
            'por_pagina' => !array_key_exists('por_pagina', $this->all()) ?
            10 :
            $this->por_pagina,
        ]);
    }
}
