<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\BusquedaRequestTrait;
use App\Http\Requests\Traits\ConEliminadosRequestTrait;
use App\Http\Requests\Traits\OrdenDireccionRequestTrait;
use App\Http\Requests\Traits\PaginacionRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class PaginacionConEliminadosOrdenDireccionBusquedaRequest extends FormRequest
{
    use PaginacionRequestTrait;
    use ConEliminadosRequestTrait;
    use OrdenDireccionRequestTrait;
    use BusquedaRequestTrait;

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
        return array_merge(
            $this->paginacionRules(),
            $this->conEliminadosRules(),
            $this->ordenDireccionRules(),
            $this->busquedaRules(),
        );
    }

     /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->paginacionPrepareForValidation();
        $this->conEliminadosPrepareForValidation();
        $this->ordenDireccionPrepareForValidation();
    }
}
