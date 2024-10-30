<?php

namespace App\Http\Requests\Traits;

trait IndexArticuloControllerRequestTrait
{
    use PaginacionRequestTrait;
    use ConEliminadosRequestTrait;
    use OrdenDireccionRequestTrait;
    use BusquedaRequestTrait;
    use SinPaginacionRequestTrait;
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function indexArticuloControllerRequestRules(): array
    {
        $reglas = [
            'categoria_id' => ['nullable', 'integer', 'exists:categorias,id'],
        ];

        return array_merge(
            $this->paginacionRules(),
            $this->conEliminadosRules(),
            $this->ordenDireccionRules(),
            $this->busquedaRules(),
            $this->sinPaginacionRules(),
            $reglas,
        );
    }

    protected function indexArticuloControllerRequestPrepareForValidation(): void
    {
        $this->paginacionPrepareForValidation();
        $this->conEliminadosPrepareForValidation();
        $this->ordenDireccionPrepareForValidation();
        $this->sinPaginacionPrepareForValidation();
    }
}
