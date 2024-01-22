<?php

namespace App\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ActualizarCategoriaRequest extends FormRequest
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
                'present',
                'string',
                'max:100',
                Rule::unique('categorias', 'nombre')->ignore($this->route('categoria')),
            ],
            'categoria_padre_id' => [
                'nullable',
                // new CategoriaPadreActivaRule(),
                function (string $attribute, mixed $value, Closure $fail) {
                    if (!DB::table('categorias')->where('id', $value)->exists()) {
                        $fail("{$attribute} debe ser una categoría existente");
                    }
                },
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value == $this->route('categoria')) {
                        $fail("{$attribute} no puede ser la misma categoría");
                    }
                },
            ],
        ];
    }
}
