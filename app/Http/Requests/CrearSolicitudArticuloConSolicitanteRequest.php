<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\CrearSolicitudArticuloRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CrearSolicitudArticuloConSolicitanteRequest extends FormRequest
{
    use CrearSolicitudArticuloRequestTrait;
    
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
            $this->crearSolicitudArticuloRules(),
            [
                'solicitante_id' => ['required', 'integer', 'exists:usuarios,id'],
            ],
        );
    }
}
