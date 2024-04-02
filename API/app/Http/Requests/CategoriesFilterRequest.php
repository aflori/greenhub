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
            "category" => "nullable|exists:categories,name",
            "label" => "nullable|exists:labels,name",
            "brand" => "nullable|exists:brands,name",
            "name" => "nullable|regex:/^[\pL\s]+$/u",
            /*
             * the regular expression means:
             * /^ = from the start of the string
             * []+ = as many long as next sequence
             * $/ = to the end of string
             * so the rules '\pL' (only unicode letters) and '\s' (whitespace) must be applied to all the string
             */
            "discount" => "nullable|boolean",
            // values are maxed at 9 because of seeder but must be maxed at 5 later
            "environmentImpact" => "nullable|missing_with:rating|numeric|max:9|min:0",
            //removed as this one need some database change
            // "rating" => "nullable|missing_with:environmentImpact|numeric|max:9|min:0",
        ];
    }
}
