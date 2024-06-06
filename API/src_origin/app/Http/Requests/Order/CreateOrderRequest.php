<?php

namespace App\Http\Requests\Order;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
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
            // the total amount of the order decided with taxes and shiping fee included
            'total_amount' => 'required|numeric',
            // the total amount of shipping fee
            'shipping_fee' => 'nullable|numeric',
            // the wanted delivery date at format 05/04/2024
            'delivery_date' => 'present_with:shipping_fee|date',
            // the facturation adress
            'facturation_adress' => 'required|array',
            'facturation_adress.road_number' => 'required|integer',
            'facturation_adress.road_name' => 'required|regex:/^[\\pL\\s-]+$/u',
            'facturation_adress.city' => 'required|alpha_dash',
            'facturation_adress.zip_code' => 'required|alpha_num',
            // the delivery adress if relevent
            'delivery_adress' => 'present_with:shipping_fee|array',
            'delivery_adress.road_number' => 'present_with:shipping_fee|integer',
            'delivery_adress.road_name' => 'present_with:shipping_fee|regex:/^[\\pL\\s-]+$/u',
            'delivery_adress.city' => 'present_with:shipping_fee|alpha_dash',
            'delivery_adress.zip_code' => 'present_with:shipping_fee|alpha_num',
            // the list of product buyed
            'products' => 'required|array',
            // list of id of selected products (uuid)
            'products.*' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer',
        ];
    }
}
