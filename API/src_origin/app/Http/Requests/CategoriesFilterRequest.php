<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoriesFilterRequest extends FormRequest
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
            // filter product by its brand name
            'brand' => 'nullable|exists:brands,name',
            // filter product by one of its category name
            'category' => 'nullable|exists:categories,name',
            // filter product by one of its labels name
            'label' => 'nullable|exists:labels,name',
            // filter product list by product containing that string
            'name' => "nullable|regex:/^[\pL\s]+$/u",
            // filter product wether it has a discount
            'discount' => 'nullable|boolean',
            // filter product by a minimal environment impact and sort it by its values
            'environmentImpact' => 'nullable|missing_with:rating|numeric|max:9|min:0',

            //removed as this one need some database change
            // "rating" => "nullable|missing_with:environmentImpact|numeric|max:9|min:0",
        ];
        /*
         * The name regular expression means:
         * /^ = from the start of the string
         * []+ = as many long as next sequence
         * $/ = to the end of string
         * so the rules '\pL' (only unicode letters) and '\s' (whitespace) must be applied to all the string
         */
    }
}
