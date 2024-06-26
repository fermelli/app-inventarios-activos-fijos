<?php

namespace App\Http\Requests;

use App\Models\Articulo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CrearArticuloRequest extends FormRequest
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
            'categoria_id' => ['required', 'exists:categorias,id'],
            'unidad_id' => ['required', 'exists:unidades,id'],
            'ubicacion_id' => ['required', 'exists:ubicaciones,id'],
            'codigo' => ['required', 'string', 'max:100', 'unique:articulos,codigo'],
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('articulos', 'nombre')->where('tipo', Articulo::TIPO_ALMACENABLE),
            ],
        ];
    }
}
