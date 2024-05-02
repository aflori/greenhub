<?php
namespace App\Http\Controllers\Api\Filter;

class ProductBrandFilter
{
    protected function getFilterAction(string $valueTested): callable
    {
        $columnName = "name";

        return function ($query) use ($columnName, $valueTested) {
            $query->where($columnName, $valueTested);
        };
    }

    function __invoke($query, string $brandName)
    {
        $relationName = 'brand';

        return $query->whereHas($relationName, $this->getFilterAction($brandName));
    }
}