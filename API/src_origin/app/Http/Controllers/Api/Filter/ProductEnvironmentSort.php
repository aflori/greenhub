<?php

namespace App\Http\Controllers\Api\Filter;

class ProductEnvironmentSort
{
    public function __invoke($query, $minimalValue)
    {
        $columnName = 'environmental_impact';
        $sortOrder = ' DESC';
        // start with a whitespace for the request purpose
        // (`ORDER BY environmental_impact DESC` SQL request part)

        return $query->where($columnName, '>=', $minimalValue)
            ->orderByRaw($columnName.$sortOrder);
    }
}
