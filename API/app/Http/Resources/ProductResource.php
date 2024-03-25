<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

use Illuminate\Support\Facades\DB;


class ProductResource extends JsonResource
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
            "name" => $this->name,
            "price" => $this->price,
            "vat_rate" => $this->vat_rate,
            "stock" => $this->stock,
            "description" => $this->description,
            "environmental_impact" => $this->environmental_impact,
            "origin" => $this->origin,
            "measuring_unit" => $this->measuring_unit,
            "measure" => $this->measure,
            "brand" => $this->getBrand(),
            "discount" => $this->getDiscount(),
            "labels" => $this->getsLabelArray(),
            "categories" => $this->getCategoriesArray(),
            "comments" => $this->getCommentsArray(),
        ];
    }

    protected function getCommentsArray() {
        $comments = $this->comments;

        for($i=0;$i< count($comments); $i++) {
            $comments[$i] = [
                "title" => $comments[$i]->title,
                "comment" => $comments[$i]->comment,
                "rating" => $comments[$i]->rating,
            ];
        }

        return $comments;
    }

    protected function getCategoriesArray() {
        $categories = $this->categories;
        for($i = 0; $i < count($categories); $i++)
        {
            $categories[$i] = $categories[$i]->name;
        }

        return $categories;
    }

    protected function getsLabelArray() {
        $labels = $this->labels;

        for($i = 0; $i < count($labels); $i++)
        {
            $labels[$i] = $labels[$i]->name;
        }

        return $labels;
    }

    protected function getDiscount() {
        $discount = $this->discount;
        if($this->discount !== null)
        {
            $discount = [
                $discount->amount,
                $discount->is_percentage
            ];
        }

        return $discount;
    }

    protected function getBrand() {
        return $this->brand->name;
    }

}
