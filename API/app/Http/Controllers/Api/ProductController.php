<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Controllers\Api\Filter\ProductFilter;
use App\Http\Requests\CategoriesFilterRequest;
use App\Http\Requests\EditOrCreateProductRequest;
use Illuminate\Support\Facades\Gate;

use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductCollection;

class ProductController extends Controller
{
    public function index(CategoriesFilterRequest $request)
    {
        $query = Product::query();
        $filteringField = $request->validated();

        $filterAction = new ProductFilter;
        $filterAction($query, $filteringField);

        $productsListFiltered = $query->get();

        return new ProductCollection($productsListFiltered);
    }

    public function store(Request $request)
    {
        $newProduct = new Product();
        foreach ($request->request as $columnName => $columnValue) {
            $newProduct->$columnName = $columnValue;
        }
        $newProduct->save();
        return $newProduct;
    }

    public function show(Product $product ) {
        return new ProductResource($product);
    }

    public function update(EditOrCreateProductRequest $request, Product $product)
    {
        // if ( isset($request->id) ) {
        //     return response()->json(["error" => "cannot change id"], 404);
        // }

        // foreach ($request->request as $attributeName => $attributeValue) {
        //     $product->$attributeName = $attributeValue;
        // }
        // $product->save();
        // return $product;

        return [ "success" => true ];
    }

    public function destroy(Product $product) {
        if(Gate::denies('is_admin')) {
            return response()->json(["error" => "not authorized to delete products"], 401);
        }
        $fieldToDetach = [
            "labels",
            "categories",
        ];

        $fieldToDelete = [
            "comments",
            "images",
        ];

        cleanRelation($product, $fieldToDetach, $fieldToDelete);

        $product->delete();
        return $product;
    }

    public function comment(Request $request, Product $product) {
        return ["comment" => "created"];
    }
}

function cleanRelation(Product $product, array $fieldToDetach, array $fieldToDelete ){
    foreach( $fieldToDetach as $field) {
        deleteManyToManyRelation($product, $field);
    }

    foreach( $fieldToDelete as $field) {
        deleteSimpleRelation($product, $field);
    }
}

function deleteSimpleRelation(Product $product, string $relationName) {
    $entities = $product->$relationName;
    foreach( $entities as $entity ) {
        $entity->delete();
    }
}

function deleteManyToManyRelation(Product $product, string $relationName) {
    $entities = $product->$relationName;

    foreach( $entities as $entity) {
        $id = $entity->id;
        $product->$relationName()->detach($id);
    }
}
