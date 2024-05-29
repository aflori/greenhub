<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderRessource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $myProduct = $this->products[0];
        
        return [
            "id" => $this->id,
            "total_price" => $this->total_price,
            "products" => [
                [
                    "id" => $myProduct->id,
                    "quantity" => $myProduct->pivot->quantity,
                ],
            ],
        ];
    }
}
