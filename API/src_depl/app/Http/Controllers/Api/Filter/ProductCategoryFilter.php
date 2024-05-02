<?php

namespace App\Http\Controllers\Api\Filter;

class ProductCategoryFilter
{
    protected function getFilterAction(string $valueTested): callable
    {
        $columnName = "name";

        return function ($query) use ($columnName, $valueTested) {
            $query->where($columnName, $valueTested);
        };
    }

    function __invoke($query, string $categoryName)
    {
        $relationName = 'categories';
        return $query->whereHas($relationName, $this->getFilterAction($categoryName));
    }
}
