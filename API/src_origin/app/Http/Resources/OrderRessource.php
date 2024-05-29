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
        $result = [
            "id" => $this->id,
            "total_price" => $this->total_price,
            "products" => getProductArray($this->products),
        ];

        if ($this->hasADelivery()) {
            $result["delivery"] = [
                "fee" => $this->shipping_fee,
                "date" => $this->delivery_date,
                "adress" => [
                    "road_number" => 1,
                    "road_name" => "allée des toto",
                    "city" => "Toto",
                    "zip_code" => "00001"
                ]
            ];
        }
        return $result;
    }

    protected function hasADelivery(): bool {
        return $this->order_date != $this->delivery_date;
    }
}

function getProductArray($products): array {
    $array = [];
    foreach($products as $product) {
        $array[] = [
            "id" => $product->id,
            "quantity" => $product->pivot->quantity,
        ];
    }
    return $array;
}