<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrearUsuarioRequest extends FormRequest
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
            'nombre' => ['required', 'string', 'max:255'],
            'correo_electronico' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('usuarios', 'correo_electronico'),
            ],
            'rol' => ['required', 'string', 'in:' . User::ROL_ADMINISTRADOR . ',' . User::ROL_PERSONAL],
            'dependencia_id' => ['nullable', 'exists:ubicaciones,id'],
        ];
    }
}
