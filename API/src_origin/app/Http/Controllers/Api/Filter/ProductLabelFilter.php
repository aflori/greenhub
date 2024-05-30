<?php

namespace App\Http\Controllers\Api\Filter;

class ProductLabelFilter
{
    public function __invoke($query, string $labelName)
    {
        $relationName = 'labels';

        return $query->whereHas($relationName, $this->getFilterAction($labelName));
    }

    protected function getFilterAction(string $valueTested): callable
    {
        $columnName = 'name';

        return function ($query) use ($columnName, $valueTested) {
            $query->where($columnName, $valueTested);
        };
    }
}
