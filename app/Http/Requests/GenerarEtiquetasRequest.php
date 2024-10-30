<?php

namespace App\Http\Requests;

use App\Http\Controllers\ActivoFijoReportePdfController;
use App\Http\Requests\Traits\IndexArticuloControllerRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class GenerarEtiquetasRequest extends FormRequest
{
    use IndexArticuloControllerRequestTrait;

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
            $this->indexArticuloControllerRequestRules(),
            [
                'fila' => ['required', 'integer', 'min:1', 'max:' . ActivoFijoReportePdfController::MAXIMO_FILAS],
                'columna' => ['required', 'integer', 'min:1', 'max:' . ActivoFijoReportePdfController::MAXIMO_COLUMNAS],
            ]
        );
    }

    protected function prepareForValidation(): void
    {
        $this->indexArticuloControllerRequestPrepareForValidation();

        $this->merge([
            'fila' => !array_key_exists('fila', $this->all())
                        ? 1 : $this->fila,
            'columna' => !array_key_exists('columna', $this->all())
                        ? 1 : $this->columna,
        ]);
    }
}
