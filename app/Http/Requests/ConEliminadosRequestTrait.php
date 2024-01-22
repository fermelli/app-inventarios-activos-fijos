<?php

namespace App\Http\Requests;

trait ConEliminadosRequestTrait
{
    protected function conEliminadosRules(): array
    {
        return [
            'con_eliminados' => ['required', 'boolean'],
        ];
    }

    protected function conEliminadosPrepareForValidation(): void
    {
        $this->merge([
            'con_eliminados' => !array_key_exists('con_eliminados', $this->all()) ?
            false :
            filter_var($this->con_eliminados, FILTER_VALIDATE_BOOLEAN),
        ]);
    }
}
