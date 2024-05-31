<?php

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

function getRandomEntity(string $model): Model
{
    return $model::inRandomOrder()->first();
}
function getRandomEntityList(string $model, int $numberOfEntity = 2): Collection
{
    return $model::inRandomOrder()->limit($numberOfEntity)->get();
}

function createAssociationsTableWithChild(Model $parent, string $relationName, Collection $child, array $pivotTableValues = []): void
{
    $parent->$relationName()->attach($child, $pivotTableValues);
}
function createManyToManyRelationships(string $child, string $relationName, int $numberOfChild = 2): Closure
{
    return function (Model $model) use ($child, $numberOfChild, $relationName) {
        $entityList = getRandomEntityList($child, $numberOfChild);
        createAssociationsTableWithChild($model, $relationName, $entityList);
    };
}
function createManyToManyToAllParent(string $parent, string $child, string $relationName, int $numberOfChildPerParent, array $pivotTableValues = [])
{
    foreach ($parent::all() as $parentEntity) {
        $childEntityList = getRandomEntityList($child, 1);
        createAssociationsTableWithChild($parentEntity, $relationName, $childEntityList, $pivotTableValues);
    }
}

function getRandomElementInArray(array $array)
{
    return fake()->randomElement($array);
}
function setPolymorphicRelation(Model $polymorphicEntity, Model $childEntity, string $childNameInParentColumn, string $childKeyInParentColumn)
{
    $polymorphicEntity->$childNameInParentColumn = $childEntity::class;
    $polymorphicEntity->$childKeyInParentColumn = $childEntity->id;
}
function createPolymorphicTable(Model $entity, array $potentialChild, string $childNameInParentColumn, string $childKeyInParentColumn)
{
    $childModel = getRandomElementInArray($potentialChild);

    $childEntity = getRandomEntity($childModel);

    setPolymorphicRelation($entity, $childEntity, $childNameInParentColumn, $childKeyInParentColumn);

    $entity->save();
}
