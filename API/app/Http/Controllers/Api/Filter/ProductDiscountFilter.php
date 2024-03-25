<?php
namespace App\Http\Controllers\Api\Filter;

class ProductDiscountFilter
{

    function __invoke($query, string $categoryName)
    {
        if($categoryName) {
            return $query->whereHas('discount');
        }

        return $query->doesntHave('discount');
    }
}