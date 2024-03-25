<?php
namespace App\Http\Controllers\Api\Filter;

class ProductNameFilter
{

    function __invoke($query, string $nameSearched)
    {
        $columnName = 'name';

        $query->where($columnName, 'LIKE', '%' . $nameSearched . '%');
    }
}