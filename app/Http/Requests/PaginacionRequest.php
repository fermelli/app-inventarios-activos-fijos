<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaginacionRequest extends FormRequest
{
    use PaginacionRequestTrait;

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
        return $this->paginacionRules();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->paginacionPrepareForValidation();
    }
}
