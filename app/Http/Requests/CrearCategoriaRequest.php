<?php

namespace App\Http\Requests;

use App\Rules\CategoriaPadreActivaRule;
use Illuminate\Foundation\Http\FormRequest;

class CrearCategoriaRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string',
                'max:100',
                'unique:categorias,nombre',
            ],
            'categoria_padre_id' => [
                'nullable',
                'exists:categorias,id',
                new CategoriaPadreActivaRule(),
            ],
        ];
    }
}
