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
            $deliveryAdress = $this->deliveryAdress;

            $result["delivery"] = [
                "fee" => $this->shipping_fee,
                "date" => $this->delivery_date,
                "adress" => [
                    "road_number" => $deliveryAdress->number,
                    "road_name" => $deliveryAdress->road,
                    "city" => $deliveryAdress->city,
                    "zip_code" => getZipCode($deliveryAdress->postal_code)
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

function getZipCode(int $intValue): string {
    $strZipCode = (string) $intValue;
    while(strlen($strZipCode) < 5) {
        $strZipCode = '0' . $strZipCode;
    }
    return $strZipCode;
}