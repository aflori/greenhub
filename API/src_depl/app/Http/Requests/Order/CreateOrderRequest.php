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
            "total_amount" => "required|numeric",
            // the total amount of vat buyed
            "total_vat" => "required|numeric",
            // the total amount of the order whitout taxes
            "total_product_price" => "required|numeric",
            // the total amount of shipping fee
            "shipping_fee" => "required|numeric",
            // the wanted delivery date at format 05/04/2024
            "delivery_date" => "required|date",
            // the facturation adress
            "facturation_adress" => "required|array",
            "facturation_adress.road_number" => "required|integer",
            "facturation_adress.road_name" => "required|regex:/$[\p{l} ]+^/u",
            "facturation_adress.city" => "required|alpha_dash",
            "facturation_adress.zip_code" => "required|alpha",
            // the delivery adress if different from facturation adress
            "delivery_adress" => "nullable|array",
            "delivery_adress.road_number" => "required|integer",
            "delivery_adress.road_name" => "required|regex:/$[\p{l} ]+^/u",
            "delivery_adress.city" => "required|alpha_dash",
            "delivery_adress.zip_code" => "required|alpha",
            // the list of product buyed
            "products" => "required|array",
            "products.*" => "required|array",
            // id of selected product (uuid)
            "products.*.id" => "required|exists:products,id",
            "products.*.unit_price" => "required|numeric",
            "products.*.quantity_buyed" => "required|integer",
            "products.*.total_price" => "required|numeric"
        ];
    }
}
