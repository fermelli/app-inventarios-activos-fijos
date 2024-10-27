<?php

namespace App\Http\Requests;

use App\Http\Controllers\ActivoFijoReportePdfController;
use Illuminate\Foundation\Http\FormRequest;

class GenerarEtiquetaRequest extends FormRequest
{
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
        return [
            'fila' => ['required', 'integer', 'min:1', 'max:' . ActivoFijoReportePdfController::MAXIMO_FILAS],
            'columna' => ['required', 'integer', 'min:1', 'max:' . ActivoFijoReportePdfController::MAXIMO_COLUMNAS],
            'cantidad' => ['required', 'integer', 'min:1'],
        ];
    }

    protected function paginacionPrepareForValidation(): void
    {
        $this->merge([
            'fila' => !array_key_exists('fila', $this->all())
                        ? 1 : $this->fila,
            'columna' => !array_key_exists('columna', $this->all())
                        ? 1 : $this->columna,
            'cantidad' => !array_key_exists('cantidad', $this->all())
                        ? 1 : $this->cantidad,
        ]);
    }
}
