<?php
namespace App\Http\Controllers\Api\Filter;

class ProductFilter
{
    protected $filtersTypeAction = [
        'category' => ProductCategoryFilter::class,
        'discount' => ProductDiscountFilter::class,
        'brand' => ProductBrandFilter::class,
        'environmentImpact' => ProductEnvironmentSort::class,
        'label' => ProductLabelFilter::class,
        'name' => ProductNameFilter::class,
    ];

    protected function doFilter($productList, string $filterType, string $filterValue)
    {
        $filterAction = new $this->filtersTypeAction[$filterType];
        $filterAction($productList, $filterValue);
    }

    function __invoke($productList, array $filtersList) {
        foreach ($filtersList as $filterType => $filterValue) {
            $this->doFilter($productList, $filterType, $filterValue);
        }
    }

}