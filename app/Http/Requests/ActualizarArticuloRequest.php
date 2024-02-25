<?php

namespace App\Http\Requests;

use App\Models\Articulo;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActualizarArticuloRequest extends FormRequest
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
            'categoria_id' => ['present', 'exists:categorias,id'],
            'unidad_id' => ['present', 'exists:unidades,id'],
            'ubicacion_id' => ['present', 'exists:ubicaciones,id'],
            'codigo' => [
                'present',
                'string',
                'max:100',
                Rule::unique('articulos', 'codigo')->ignore($this->route('articulo')),
            ],
            'nombre' => [
                'present',
                'string',
                'max:255',
                Rule::unique('articulos', 'nombre')
                    ->ignore($this->route('articulo'))
                    ->where('tipo', Articulo::TIPO_ALMACENABLE),
            ],
        ];
    }
}
