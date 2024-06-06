<?php
 
declare(strict_types=1);
 
use Rector\Config\RectorConfig;
use RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/public',
        __DIR__ . '/resources',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);
 
    $rectorConfig->sets([
        SetList::DEAD_CODE,
        LevelSetList::UP_TO_PHP_82,
    ]);

    $rectorConfig->rule(RectorLaravel\Rector\If_\AbortIfRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\Class_\AddExtendsAnnotationToModelFactoriesRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\New_\AddGuardToLoginEventRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\ClassMethod\AddParentBootToModelClassMethodRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\Class_\AnonymousMigrationsRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\Assign\CallOnAppArrayAccessToStandaloneAssignRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\ChangeQueryWhereDateValueWithCarbonRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\Cast\DatabaseExpressionCastsToMethodCallRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\DatabaseExpressionToStringToMethodCallRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\StaticCall\EloquentMagicMethodToQueryBuilderRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\EloquentOrderByToLatestOrOldestRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\EloquentWhereRelationTypeHintingParameterRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\Empty_\EmptyToBlankAndFilledFuncRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\EloquentWhereTypeHintClosureParameterRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\FactoryApplyingStatesRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\FuncCall\FactoryFuncCallToStaticCallRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\JsonCallToExplicitJsonCallRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\LumenRoutesStringActionToUsesArrayRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\ClassMethod\MigrateToSimplifiedAttributeRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\FuncCall\NotFilledBlankFuncCallToBlankFilledFuncCallRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\FuncCall\NowFuncWithStartOfDayMethodCallToTodayFuncRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\RedirectRouteToToRouteHelperRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\FuncCall\RemoveDumpDataDeadCodeRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\If_\ReportIfRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\StaticCall\RequestStaticValidateToInjectRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\StaticCall\RouteActionCallableRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\Expr\SubStrToStartsWithOrEndsWithStaticMethodCallRector\SubStrToStartsWithOrEndsWithStaticMethodCallRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\FuncCall\ThrowIfAndThrowUnlessExceptionsToUseClassStringRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\If_\ThrowIfRector::class);
    $rectorConfig->rule(RectorLaravel\Rector\MethodCall\AssertStatusToAssertMethodRector::class);
};
