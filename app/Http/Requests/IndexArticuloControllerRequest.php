<?php

namespace App\Http\Requests;

use App\Http\Requests\Traits\IndexArticuloControllerRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class IndexArticuloControllerRequest extends FormRequest
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
        return $this->indexArticuloControllerRequestRules();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->indexArticuloControllerRequestPrepareForValidation();
    }
}
