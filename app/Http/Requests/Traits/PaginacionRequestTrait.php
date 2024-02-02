<?php

namespace App\Http\Requests\Traits;

trait PaginacionRequestTrait
{
    protected function paginacionRules(): array
    {
        return [
            'pagina' => ['required', 'integer', 'min:1'],
            'items_por_pagina' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function paginacionPrepareForValidation(): void
    {
        $this->merge([
            'pagina' => !array_key_exists('pagina', $this->all()) ?
            1 :
            $this->pagina,
            'items_por_pagina' => !array_key_exists('items_por_pagina', $this->all()) ?
            10 :
            $this->items_por_pagina,
        ]);
    }
}
