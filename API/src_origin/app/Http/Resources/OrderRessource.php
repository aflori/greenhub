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
        
        
        return [
            "id" => $this->id,
            "total_price" => $this->total_price,
            "products" => getProductArray($this->products),
        ];
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