<?php

namespace App\Http\Requests\Traits;

trait SinPaginacionRequestTrait
{
    protected function sinPaginacionRules(): array
    {
        return [
            'sin_paginacion' => ['nullable', 'boolean'],
        ];
    }

    protected function sinPaginacionPrepareForValidation(): void
    {
        $this->merge([
            'sin_paginacion' => !array_key_exists('sin_paginacion', $this->all())
                                ? false : $this->boolean('sin_paginacion'),
        ]);
    }
}
