<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ActualizarUsuarioRequest extends FormRequest
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
            'rol' => ['required', 'string', 'in:' . User::ROL_ADMINISTRADOR . ',' . User::ROL_PERSONAL],
            'dependencia_id' => ['required', 'exists:ubicaciones,id'],
        ];
    }
}
